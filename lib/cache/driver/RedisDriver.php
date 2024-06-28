<?php
/**
 * The cache library of zentaopms.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      dingguodong <dingguodong@easycorp.ltd>
 * @package     cache
 * @link        https://www.zentao.net
 */

namespace ZenTao\Cache\Driver;

use ZenTao\Cache\SimpleCache\CacheInterface;
use ZenTao\Cache\SimpleCache\InvalidArgumentException;

class RedisDriver implements CacheInterface
{
    /**
     * @var string
     */
    private $namespace;

    /**
     * @var int
     */
    private $defaultLifetime;

    public function __construct($namespace = '', $defaultLifetime = 0)
    {
        $this->namespace = $namespace;
        $this->defaultLifetime = $defaultLifetime;

        global $app;
        $this->redis = $app->redis;
    }

    /**
     * Get the value related to the specified key.
     *
     * @link   https://github.com/phpredis/phpredis?tab=readme-ov-file#get
     * @param  mixed $key
     * @param  mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $this->assertKeyName($key);
        $key = $this->buildKeyName($key);

        $value = $this->redis->get($key);

        return $value ? unserialize($value) : $default;
    }

    /**
     * Set the string value in argument as value of the key.
     *
     * @param  mixed $key
     * @param  mixed $value
     * @param  mixed $ttl
     * @return void
     */
    public function set($key, $value, $ttl = null)
    {
        $this->assertKeyName($key);
        $key = $this->buildKeyName($key);

        $ttl = is_null($ttl) ? $this->defaultLifetime : $ttl;

        return $this->redis->set($key, serialize($value), (int)$ttl);
    }

    /**
     * Remove specified keys.
     *
     * @link   https://github.com/phpredis/phpredis?tab=readme-ov-file#del-delete-unlink
     * @param  mixed $key
     * @return void
     */
    public function delete($key)
    {
        $this->assertKeyName($key);
        $key = $this->buildKeyName($key);

        return $this->redis->del($key);
    }

    /**
     * Remove all keys from the current database.
     *
     * @link   https://github.com/phpredis/phpredis?tab=readme-ov-file#flushdb
     * @return Redis|bool
     */
    public function clear()
    {
        return $this->redis->flushDb();
    }

    /**
     * Get the values of all the specified keys.
     *
     * @param  array $keys
     * @param  mixed $default
     * @return array
     */
    public function getMultiple($keys, $default = null)
    {
        $this->assertKeyNames($keys);
        $keys = $this->buildKeyNames($keys);

        $result = $this->redis->mget($keys);

        if(!is_null($default) && is_array($result) && count($keys) > count($result))
        {
            $notFoundKeys = array_diff($keys, array_keys($result));
            $result       = array_merge($result, array_fill_keys($notFoundKeys, $default));
        }

        $mappedResult = array();

        foreach($result as $key => $value)
        {
            $key = preg_replace("/^$this->namespace/", '', $key);

            $mappedResult[$key] = unserialize($value);
        }

        return $mappedResult;
    }

    /**
     * Sets multiple key-value pairs in one atomic command.
     *
     * @link   https://github.com/phpredis/phpredis?tab=readme-ov-file#mset-msetnx
     * @param  mixed $values
     * @param  mixed $ttl
     * @return bool
     */
    public function setMultiple($values, $ttl = null)
    {
        $this->assertKeyNames(array_keys($values));

        $mappedByNamespaceValues = array();

        foreach($values as $key => $value)
        {
            $mappedByNamespaceValues[$this->buildKeyName($key)] = serialize($value);
        }

        $ttl = is_null($ttl) ? $this->defaultLifetime : $ttl;

        if(!empty($ttl))
        {
            foreach($mappedByNamespaceValues as $key => $value)
            {
                $this->redis->set($key, $value, $ttl);
            }
        }

        return $this->redis->mset($mappedByNamespaceValues);
    }

    /**
     * Delete the multiple keys.
     *
     * @param  array $keys
     * @return bool
     */
    public function deleteMultiple($keys)
    {
        $this->assertKeyNames($keys);
        $keys = $this->buildKeyNames($keys);

        $result = array();
        foreach($keys as $key)
        {
            $isDeleted = $this->delete($key);
            if($isDeleted) $result[] = $isDeleted;
        }

        return count($result) === count($keys) ? true : false;
    }

    /**
     * Verify if the specified key exists.
     *
     * @link   https://github.com/phpredis/phpredis?tab=readme-ov-file#exists
     * @param  mixed $key
     * @return bool
     */
    public function has($key)
    {
        $this->assertKeyName($key);
        $key = $this->buildKeyName($key);

        return (bool) $this->redis->exists($key);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function buildKeyName($key)
    {
        return $this->namespace . $key;
    }

    /**
     * @param string[] $keys
     *
     * @return string[]
     */
    private function buildKeyNames(array $keys)
    {
        return array_map(function($key) {
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
