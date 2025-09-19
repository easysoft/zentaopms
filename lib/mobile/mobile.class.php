<?php
require_once dirname(__FILE__, 2) . '/base/delegate/delegate.class.php';

class mobile extends baseDelegate
{
    protected static $className = 'Mobile_Detect';

    public function __construct(?array $headers = null, $userAgent = null)
    {
        $this->instance = new self::$className($headers, $userAgent);
    }
}
