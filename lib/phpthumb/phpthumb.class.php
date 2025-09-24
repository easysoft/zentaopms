<?php
require_once dirname(__FILE__, 2) . '/base/delegate/delegate.class.php';

class phpthumb extends baseDelegate
{
    protected static $className = 'PHPThumb\GD';

    public function __construct($fileName, $options = array(), array $plugins = array())
    {
        $this->instance = new self::$className($fileName, $options, $plugins);
    }
}
