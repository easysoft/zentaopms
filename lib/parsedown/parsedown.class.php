<?php
require_once dirname(__FILE__, 2) . '/base/delegate/delegate.class.php';

class parsedown extends baseDelegate
{
    protected static $className = 'Vendor\Parsedown\Parsedown';

    public function __construct()
    {
        $this->instance = new self::$className();
    }
}
