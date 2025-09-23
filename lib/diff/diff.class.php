<?php
require_once dirname(__FILE__, 2) . '/base/delegate/delegate.class.php';

class diff extends baseDelegate
{
    protected static $className = 'GorHill\FineDiff\FineDiff';

	public function __construct($from_text = '', $to_text = '', $granularityStack = null)
    {
        $this->instance = new self::$className($from_text, $to_text, $granularityStack);
    }
}
