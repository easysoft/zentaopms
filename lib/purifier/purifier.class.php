<?php
require_once dirname(__FILE__, 2) . '/base/delegate/delegate.class.php';

class purifier extends baseDelegate
{
    protected static $className = 'HTMLPurifier';

    private $config = ['Cache.DefinitionImpl' => null];

    public function __construct($config = [])
    {
        $purifierConfig = HTMLPurifier_Config::createDefault();
        foreach ($config as $key => $value) {
            $purifierConfig->set($key, $value);
        }
        $this->instance = new self::$className($purifierConfig);
    }
}
