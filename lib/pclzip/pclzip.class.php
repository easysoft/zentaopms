<?php
require_once dirname(__FILE__, 2) . '/base/delegate/delegate.class.php';

class pclzip extends baseDelegate
{
    protected static $className = 'PclZip';

    public function __construct($p_zipname)
    {
        $this->instance = new self::$className($p_zipname);
    }
}
