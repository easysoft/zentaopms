<?php
require_once dirname(__FILE__, 2) . '/base/delegate/delegate.class.php';

class captcha extends baseDelegate
{
    protected static $className = 'Gregwar\Captcha\CaptchaBuilder';

    public function __construct($phrase = null, ?PhraseBuilderInterface $builder = null)
    {
        $this->instance = new self::$className($phrase, $builder);
    }
}
