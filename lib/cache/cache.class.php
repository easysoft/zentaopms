<?php
/**
 * The cache library of zentaopms.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Lu Fei <lufei@easycorp.ltd>
 * @package     cache
 * @link        http://www.zentao.net
 */

helper::import(dirname(__FILE__) . DS . 'simple-cache' . DS . 'CacheInterface.php');
helper::import(dirname(__FILE__) . DS . 'simple-cache' . DS . 'CacheException.php');
helper::import(dirname(__FILE__) . DS . 'simple-cache' . DS . 'InvalidArgumentException.php');
helper::import(dirname(__FILE__) . DS . 'driver' . DS . 'ApcuDriver.php');
helper::import(dirname(__FILE__) . DS . 'driver' . DS . 'YacDriver.php');

use ZenTao\Cache\SimpleCache\InvalidArgumentException;

class cache
{
    /**
     * @var ZenTao\Cache\SimpleCache\CacheInterface
     */
    protected $client;

    public function __construct($driver = 'Apcu', $namespace = '', $defaultLifetime = 0)
    {
        $driver = ucfirst(strtolower($driver));
        switch($driver)
        {
            case 'Apcu':
                $className = 'ZenTao\Cache\Driver\ApcuDriver';
                break;
            case 'Yac':
                $className = 'ZenTao\Cache\Driver\YacDriver';
                break;
            default:
                throw new InvalidArgumentException("Driver {$driver} is not supported.");
        }

        if(!extension_loaded($driver)) throw new InvalidArgumentException("Driver ext-{$driver} is not loaded.");

        $this->client = new $className($namespace, $defaultLifetime);
    }

    public function __call($name, $arguments)
    {
        if(!method_exists($this->client, $name)) throw new InvalidArgumentException("Method {$name} does not exist.");

        return call_user_func_array(array($this->client, $name), $arguments);
    }
}
