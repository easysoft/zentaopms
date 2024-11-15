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

class ApcuDriver implements CacheInterface
{
    /**
     * 缓存命名空间。
     * The cache namespace.
     *
     * @access private
     * @var string
     */
    private $namespace;

    /**
     * 缓存默认生命周期。
     * The cache default lifetime.
     *
     * @access private
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

    public function __construct($namespace = '', $defaultLifetime = 0, $scope = '', $connector = '')
    {
        $this->namespace       = $namespace;
        $this->defaultLifetime = $defaultLifetime;
        $this->scope           = $scope;
        $this->connector       = $connector;
    }

    public function get($key, $default = null)
    {
        $value = apcu_fetch($key, $success);

        return $success === false ? $default : $value;
    }

    public function set($key, $value, $ttl = null)
    {
        $ttl = (int)($ttl ?: $this->defaultLifetime);

        return apcu_store($key, $value, $ttl);
    }

    public function delete($key)
    {
        return apcu_delete($key);
    }

    public function clear()
    {
        if($this->scope == 'private') return apcu_clear_cache();

        $keys      = [];
        $info      = apcu_cache_info();
        $cacheList = $info['cache_list'];
        foreach($cacheList as $cache)
        {
            if(strpos($cache['info'], $this->namespace . $this->connector) === 0) $keys[] = $cache['info'];
        }
        if(!$keys) return true;

        return $this->deleteMultiple($keys);
    }

    public function getMultiple($keys, $default = null)
    {
        $values = apcu_fetch($keys);
        if($values === false) return [];

        return $values;
    }

    public function setMultiple($values, $ttl = null)
    {
        $ttl = (int)($ttl ?: $this->defaultLifetime);

        $result = apcu_store($values, $ttl);

        return $result === true ? true : (is_array($result) && count($result) == 0 ? true : false);
    }

    public function deleteMultiple($keys)
    {
        $result = apcu_delete($keys);

        return count($result) === count($keys) ? false : true;
    }

    public function has($key)
    {
        return (bool) apcu_exists($key);
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
        $info = apcu_sma_info(true);

        if($type == 'total') return \helper::formatKB($info['seg_size']);
        if($type == 'free')  return \helper::formatKB($info['avail_mem']);
        if($type == 'used')  return \helper::formatKB($info['seg_size'] - $info['avail_mem']);
        if($type == 'rate')  return round(($info['seg_size'] - $info['avail_mem']) / $info['seg_size'] * 100, 2);
        return '';
    }
}
