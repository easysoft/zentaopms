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
        return apcu_clear_cache();
    }

    public function getMultiple($keys, $default = null)
    {
        return apcu_fetch($keys);
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
