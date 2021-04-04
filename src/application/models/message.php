<?php
declare(strict_types=1);

use Shared\Model as Model

class Message extends Model
{
    /**
    * @column
    * @readwrite
    * @type text
    * @length 256
    *
    * @validate required
    * @label body
    */
    protected string $_body;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected int $_message;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected int $_user;

    public function getReplies(): Message
    {
        return self::all(array(
            "message = ?" => $this->getId(),
            "live = ?" => true,
            "deleted = ?" => false
        ), array(
            "*",
            "(SELECT CONCAT(first, \" \", last) FROM user WHERE user.id = message.user)" => "user_name"
        ), "created", "desc");
    }
    public static function fetchReplies(int $id): Message
    {
        $message = new Message(array(
            "id" => $id
        ));
        return $message->getReplies();
    }

}
