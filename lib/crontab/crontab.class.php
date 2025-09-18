<?php
require_once dirname(__FILE__, 2) . '/base/delegate/delegate.class.php';

class crontab extends baseDelegate
{
    protected static $className = 'Cron\CronExpression';

    public function __construct($expression, ?FieldFactory $fieldFactory = null)
    {
        $this->instance = new self::$className($expression, $fieldFactory);
    }
}
