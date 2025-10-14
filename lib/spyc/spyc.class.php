<?php
require_once dirname(__FILE__, 2) . '/base/delegate/delegate.class.php';

class spyc extends baseDelegate
{
    protected static $className = 'Spyc';

    public function __construct()
    {
        $this->instance = new self::$className();
    }
}
