<?php
declare(strict_types=1);

use Shared\Model as Model

class User extends Model
{
    /**
    * @column
    * @readwrite
    * @primary
    * @type autonumber
    */
    protected $_id;

    /**
    * @column
    * @readwrite
    * @type text
    * @length 100
    *
    * @validate required, alpha, min(3), max(32)
    * @label first name
    */
    protected $_first;

    /**
    * @column
    * @readwrite
    * @type text
    * @length 100
    *
    * @validate required, alpha, min(3), max(32)
    * @label last name
    */
    protected $_last;

    /**
    * @column
    * @readwrite
    * @type text
    * @length 100
    * @index
    *
    * @validate required, max(100)
    * @label email address
    */
    protected $_email;

    /**
    * @column
    * @readwrite
    * @type text
    * @length 100
    * @index
    *
    * @validate required, min(8), max(32)
    * @label password
    */
    protected string $_password;

    /**
    * @column
    * @readwrite
    * @type boolean
    */
    protected bool $_admin = false;


    public function isFriend(int $id): bool
    {
        $friend = Friend::first(array(
            "user" => $this->getId(),
            "friend" => $id
        ));

        if ($friend)
        {
            return true;
        }
        return false;
    }

    public static function hasFriend(int $id, User $friend): bool
    {
        $user = new self(array(
            "id" => $id
        ));
        return $user->isFriend($friend);
    }

    public function getFile(): object
    {
        return File::first(array(
            "user = ?" => $this->id,
            "live = ?" => true,
            "deleted = ?" => false
        ), array("*"), "id", "DESC");
    }
}
