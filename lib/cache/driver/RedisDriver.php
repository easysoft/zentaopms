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
     * 缓存命名空间，用来区分不同的缓存。
     * The cache namespace, used to distinguish different caches.
     *
     * @var string
     */
    private $namespace;

    /**
     * 缓存过期时间，单位为秒。
     * The cache expiration time, in seconds.
     *
     * @var int
     */
    private $defaultLifetime;

    /**
     * 缓存服务范围。private 独享|public 共享。
     * The cache scope.
     *
     * @access private
     * @var string
     */
    private $scope;

    /**
     * 缓存键连接符。
     * Cache key connector.
     *
     * @access private
     * @var string
     */
    private $connector;

    public function __construct($namespace = '', $defaultLifetime = 0, $scope = '', $connector = '', $setting = null)
    {
        $this->namespace       = $namespace;
        $this->defaultLifetime = $defaultLifetime;
        $this->scope           = $scope;
        $this->connector       = $connector;

        $this->connectRedis($setting);
    }

    /**
     * 连接 Redis 服务器。
     * Connect to the Redis server.
     *
     * @param  object $setting
     * @access private
     * @return object
     */
    private function connectRedis($setting)
    {
        global $config;

        try
        {
            $this->redis = \helper::connectRedis($setting);
            $this->redis->setOption(\Redis::OPT_SERIALIZER, $this->getSerializer($setting->serializer));
            $this->redis->select($setting->database);
        }
        catch(Exception $e)
        {
            \helper::end($e->getMessage());
        }
    }

    /**
     * 设置序列化器。
     * Set the serializer.
     *
     * @param  string $serializer
     * @access private
     * @return void
     */
    private function getSerializer($serializer)
    {
        if($serializer == 'igbinary') return \Redis::SERIALIZER_IGBINARY;
        if($serializer == 'php')      return \Redis::SERIALIZER_PHP;
        if($serializer == 'msgpack')  return \Redis::SERIALIZER_MSGPACK;
        if($serializer == 'json')     return \Redis::SERIALIZER_JSON;
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
        $value = $this->redis->get($key);

        return $value ? $value : $default;
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
        $ttl = (int)($ttl ?: $this->defaultLifetime);

        return $this->redis->set($key, $value, $ttl ?: null);
    }

    /**
     * Remove specified keys.
     *
     * @link   https://github.com/phpredis/phpredis?tab=readme-ov-file#del-delete-unlink
     * @param  mixed $key
     * @return int
     */
    public function delete($key)
    {
        return $this->redis->del($key);
    }

    /**
     * Remove all keys from the current database.
     *
     * @link   https://github.com/phpredis/phpredis?tab=readme-ov-file#flushdb
     * @return bool
     */
    public function clear()
    {
        if($this->scope == 'private') return $this->redis->flushDB();

        /* With Redis::SCAN_RETRY enabled */
        $this->redis->setOption(\Redis::OPT_SCAN, \Redis::SCAN_RETRY);

        $it   = null;
        $keys = [];

        while($cachedKeys = $this->redis->scan($it, $this->namespace . $this->connector . '*'))
        {
            $keys = array_merge($keys, $cachedKeys);
        }

        return $this->deleteMultiple($keys);
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
        return array_filter(array_combine($keys, $this->redis->mget($keys)));
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
        $ttl = (int)($ttl ?: $this->defaultLifetime);

        if(!$ttl) return $this->redis->mset($values);

        foreach($values as $key => $value) $this->redis->set($key, $value, $ttl);
    }

    /**
     * Delete the multiple keys.
     *
     * @param  array $keys
     * @return bool
     */
    public function deleteMultiple($keys)
    {
        $result = $this->redis->del($keys);

        return $result === count($keys);
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
        return (bool) $this->redis->exists($key);
    }

    /**
     * 关闭 Redis 连接。
     * Close the Redis connection.
     *
     * @access public
     * @return bool
     */
    public function close()
    {
        return $this->redis->close();
    }

    /**
     * 获取内存使用情况。
     * Get memory usage.
     *
     * @param  string $type
     * @return string
     */
    public function memory($type)
    {
        $info = $this->redis->info();

        if($type == 'total') return $info['total_system_memory_human'];
        if($type == 'free')  return \helper::formatKB($info['total_system_memory'] - $info['used_memory']);
        if($type == 'used')  return $info['used_memory_human'];
        if($type == 'rate')  return round(($info['used_memory'] / $info['total_system_memory']) * 100, 2);
        return '';
    }
}
