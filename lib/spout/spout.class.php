<?php
require_once dirname(__FILE__, 2) . '/base/delegate/delegate.class.php';

class spout extends baseDelegate
{
    protected static $className = 'Box\Spout\Reader\Common\Creator\ReaderFactory';

    public function __construct($readerType = 'xlsx')
    {
        $this->instance = self::$className::createFromType($readerType);
    }
}
