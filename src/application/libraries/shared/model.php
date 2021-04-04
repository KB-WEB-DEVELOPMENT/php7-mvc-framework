<?php
declare(strict_types=1);

    class Model extends \Framework\Model
    {
        /**
        * @column
        * @readwrite
        * @primary
        * @type autonumber
        */
        protected int $_id;

        /**
        * @column
        * @readwrite
        * @type boolean
        * @index
        */
        protected bool $_live;

        /**
        * @column
        * @readwrite
        * @type boolean
        * @index
        */
        protected bool $_deleted;

        /**
        * @column
        * @readwrite
        * @type datetime
        */
        protected /DateTime $_created;

        /**
        * @column
        * @readwrite
        * @type datetime
        */
        protected /Datetime $_modified;

        public function save(): void
        {
            $primary = $this->getPrimaryColumn();
            $raw = $primary["raw"];

            if (empty($this->$raw))
            {
                $this->setCreated(date("Y-m-d H:i:s"));
                $this->setDeleted(false);
                $this->setLive(true);
            }
            $this->setModified(date("Y-m-d H:i:s"));

            parent::save();
        }
  }
