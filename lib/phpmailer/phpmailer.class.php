<?php
require_once dirname(__FILE__, 2) . '/base/delegate/delegate.class.php';

class phpmailer extends baseDelegate
{
    protected static $className = 'PHPMailer\PHPMailer\PHPMailer';

    public function __construct($exceptions = null)
    {
        $this->instance = new self::$className($exceptions);
    }
}
