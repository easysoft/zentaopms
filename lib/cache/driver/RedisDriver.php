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
     * 缓存序列化器，默认使用 PHP 内置的序列化器。
     * The cache serializer, the default is the PHP built-in serializer.
     *
     * @var int
     */
    private $serializer = \Redis::SERIALIZER_PHP;

    public function __construct($namespace = '', $defaultLifetime = 0)
    {
        $this->namespace = $namespace;
        $this->defaultLifetime = $defaultLifetime;

        $this->setSerializer();

        $this->connectRedis();
    }

    /**
     * 设置序列化器。
     * Set the serializer.
     *
     * @access private
     * @return void
     */
    private function setSerializer()
    {
        if(function_exists('igbinary_serialize'))
        {
            $this->serializer = \Redis::SERIALIZER_IGBINARY;
        }
        elseif(function_exists('msgpack_pack'))
        {
            $this->serializer = \Redis::SERIALIZER_MSGPACK;
        }
    }

    /**
     * 连接 Redis 服务器。
     * Connect to the Redis server.
     *
     * @access private
     * @return object
     */
    private function connectRedis()
    {
        global $config;

        if(empty($config->redis)) \helper::end('Redis is not enabled in the configuration file.');

        try
        {
            $this->redis = new \Redis();

            $version = phpversion('redis');
            if(version_compare($version, '5.3.0', 'ge'))
            {
                $this->redis->connect($config->redis->host , $config->redis->port, $config->redis->timeout, '', 0, 0, ['auth' => [$config->redis->username, $config->redis->password]]);
            }
            else
            {
                $this->redis->connect($this->config->redis->host , $this->config->redis->port, $this->config->redis->timeout, '', 0, 0);
                $this->redis->auth(['pass' => $this->config->redis->password]);
            }

            if(!$this->redis->ping()) \helper::end('Can not connect to Redis server.');

            $this->redis->setOption(\Redis::OPT_SERIALIZER, $this->serializer);
        }
        catch(RedisException $e)
        {
            \helper::end('Can not connect to Redis server. The error message is: ' . $e->getMessage());
        }
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
        $ttl = is_null($ttl) ? $this->defaultLifetime : $ttl;

        return $this->redis->set($key, $value, (int)$ttl);
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
        /* With Redis::SCAN_RETRY enabled */
        $this->redis->setOption(\Redis::OPT_SCAN, \Redis::SCAN_RETRY);

        $it   = null;
        $keys = [];

        while($cachedKeys = $this->redis->scan($it, $this->namespace . ':*'))
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
        return $this->redis->mget($keys);
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
        $ttl = is_null($ttl) ? $this->defaultLifetime : $ttl;

        if(!empty($ttl))
        {
            foreach($values as $key => $value)
            {
                $this->redis->set($key, $value, $ttl);
            }
        }

        return $this->redis->mset($values);
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

        return count($result) === count($keys);
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
