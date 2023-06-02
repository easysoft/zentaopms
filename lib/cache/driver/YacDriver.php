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

class YacDriver implements CacheInterface
{
    /**
     * @var string
     */
    private $namespace;

    /**
     * @var int
     */
    private $defaultLifetime;

    /**
     * yac client
     *
     * @var \Yac
     */
    protected $yac;

    /**
     * if your key is longer than this, maybe you can use md5 result as the key
     */
    const KEY_MAX_LEN = 48;

    public function __construct($namespace = '', $defaultLifetime = 0)
    {
        $this->namespace = $namespace;
        $this->defaultLifetime = $defaultLifetime;
        $this->yac = new \Yac($namespace);
    }

    public function get($key, $default = null)
    {
        $this->assertKeyName($key);
        $key = $this->buildKeyName($key);

        return $this->yac->get($key) ?: $default;
    }

    public function set($key, $value, $ttl = null)
    {
        $this->assertKeyName($key);
        $key = $this->buildKeyName($key);

        $ttl = is_null($ttl) ? $this->defaultLifetime : $ttl;

        return $this->yac->set($key, $value, (int)$ttl);
    }

    public function delete($key)
    {
        $this->assertKeyName($key);
        $key = $this->buildKeyName($key);

        return $this->yac->delete($key);
    }

    public function clear()
    {
        return $this->yac->flush();
    }

    public function getMultiple($keys, $default = null)
    {
        if(!is_array($keys)) {
            return array();
        }

        $hashKeyMap = array();
        foreach($keys as $index => $key)
        {
            $this->assertKeyName($key);
            if(strlen($key) > self::KEY_MAX_LEN)
            {
                $keys[$index] = $this->buildKeyName($key);
                $hashKeyMap[$keys[$index]] = $key;
            }
        }

        $results = $this->yac->get($keys);
        if($results !== false)
        {
            foreach($results as $key => $value)
            {
                if(isset($hashKeyMap[$key]))
                {
                    $results[$hashKeyMap[$key]] = $value;
                    unset($results[$key]);
                }
            }
            return $results;
        }

        $results = array();
        foreach($keys as $key)
        {
            $results[$key] = $default;
        }
        return $results;
    }

    public function setMultiple($values, $ttl = null)
    {
        if(!is_array($values)) return false;

        foreach($values as $key => $value)
        {
            if(strlen($key) > self::KEY_MAX_LEN)
            {
                $values[$this->buildKeyName($key)] = $value;
                unset($values[$key]);
            }
        }

        $ttl = is_null($ttl) ? $this->defaultLifetime : $ttl;

        return $this->yac->set($values, $ttl);
    }

    public function deleteMultiple($keys)
    {
        foreach($keys as $index => $key)
        {
            $keys[$index] = $this->buildKeyName($key);
        }
        return $this->yac->delete($keys);
    }

    public function has($key)
    {
        return $this->get($key) !== null;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function buildKeyName($key)
    {
        if(strlen($key) > self::KEY_MAX_LEN)
        {
            $key = md5($key);
        }
        return $key;
    }

    /**
     * @param string[] $keys
     *
     * @return string[]
     */
    private function buildKeyNames(array $keys)
    {
        return array_map(function ($key) {
            return $this->buildKeyName($key);
        }, $keys);
    }

    /**
     * @param mixed $key
     *
     * @throws InvalidArgumentException
     */
    private function assertKeyName($key)
    {
        if(!is_scalar($key) || is_bool($key)) throw new InvalidArgumentException();
    }

    /**
     * @param string[] $keys
     *
     * @throws InvalidArgumentException
     */
    private function assertKeyNames(array $keys)
    {
        array_map(function ($value) {
            $this->assertKeyName($value);
        }, $keys);
    }
}
