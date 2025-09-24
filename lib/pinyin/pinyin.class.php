<?php
require_once dirname(__FILE__, 2) . '/base/delegate/delegate.class.php';

class pinyin extends baseDelegate
{
    protected static $className = 'Overtrue\Pinyin\Pinyin';

    public function __construct($loaderName = null)
    {
        $this->instance = new self::$className($loaderName);
    }
}
