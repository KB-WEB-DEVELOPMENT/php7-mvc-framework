<?php
declare(strict_types=1);

use Shared\Model as Model;

class Friend extends Model
{
    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected int $_user;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected int $_friend;
}
