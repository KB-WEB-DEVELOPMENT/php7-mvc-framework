<?php
declare(strict_types=1);

use Shared\Model as Model;

class File extends Model
{
    /**
    * @column
    * @readwrite
    * @type text
    * @length 255
    */
    protected string $_name;

    /**
    * @column
    * @readwrite
    * @type text
    * @length 32
    */
    protected string $_mime;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected int $_size;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected int $_width;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected int $_height;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected int $_user;
}
