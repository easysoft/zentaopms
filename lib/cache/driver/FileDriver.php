<?php
/**
 * The cache library of zentaopms.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Lu Fei <lufei@easycorp.ltd>
 * @package     cache
 * @link        https://www.zentao.net
 */

namespace ZenTao\Cache\Driver;

use ZenTao\Cache\SimpleCache\CacheInterface;
use ZenTao\Cache\SimpleCache\InvalidArgumentException;

class FileDriver implements CacheInterface
{
    /**
     * The file cache directory.
     *
     * @var string
     */
    protected $directory;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var int
     */
    private $defaultLifetime;

    public function __construct($namespace = '', $defaultLifetime = 0, $directory = '')
    {
        $this->namespace       = $namespace;
        $this->defaultLifetime = $defaultLifetime;
        $this->directory       = $directory;
        if(!is_dir($this->getPrefix()) && is_writable($this->directory)) mkdir($this->getPrefix(), 0777, true);
    }

    public function getCacheKey(string $key)
    {
        return $this->getPrefix() . DS . md5($key) . '.cache';
    }

    public function get($key, $default = null)
    {
        $file = $this->getCacheKey($key);
        if(!file_exists($file)) return $default;

        $content = unserialize(file_get_contents($file));
        if($this->isExpired($content))
        {
            $this->delete($key);
            return $default;
        }

        return $content['data'];
    }

    public function set($key, $value, $ttl = null)
    {
        $file    = $this->getCacheKey($key);
        $content = serialize($this->generatePayload($value, $ttl));

        $result = file_put_contents($file, $content);

        return (bool)$result;
    }

    public function delete($key)
    {
        $file = $this->getCacheKey($key);
        if(file_exists($file))
        {
            if(!is_writable($file)) return false;
            unlink($file);
        }

        return true;
    }

    public function clear()
    {
        return $this->clearPrefix('');
    }

    public function getMultiple($keys, $default = null)
    {
        if(!is_array($keys)) throw new InvalidArgumentException('The keys is invalid!');

        $result = array();
        foreach($keys as $key) $result[$key] = $this->get($key, $default);

        return $result;
    }

    public function setMultiple($values, $ttl = null)
    {
        if(!is_array($values)) throw new InvalidArgumentException('The values is invalid!');

        $time = $this->genLifeTime($ttl);
        foreach($values as $key => $value) $this->set($key, $value, $time);

        return true;
    }

    public function deleteMultiple($keys)
    {
        if(!is_array($keys)) throw new InvalidArgumentException('The keys is invalid!');

        foreach($keys as $key) $this->delete($key);

        return true;
    }

    public function has($key)
    {
        $file = $this->getCacheKey($key);

        if(!file_exists($file)) return false;

        $content = unserialize(file_get_contents($file));
        if($this->isExpired($content))
        {
            $this->delete($key);
            return false;
        }
        return true;
    }

    public function clearPrefix(string $prefix)
    {
        $files = glob($this->getPrefix() . DS . $prefix . '*');
        foreach($files as $file)
        {
            if(is_dir($file)) continue;

            unlink($file);
        }

        return true;
    }

    protected function isExpired($payload)
    {
        if(is_null($payload['time'])) return false;

        return time() > $payload['time'];
    }

    protected function generatePayload($value, $ttl = null)
    {
        $time = $this->genLifeTime($ttl);
        return array('data' => $value, 'time' => $time);
    }

    protected function genLifeTime($ttl = null)
    {
        if(is_null($ttl)) return time() + $this->defaultLifetime;
        if($ttl > time()) return $ttl;
        return time() + $ttl;
    }

    protected function getPrefix()
    {
        return $this->directory . $this->namespace;
    }
}
