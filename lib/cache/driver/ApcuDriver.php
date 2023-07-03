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
        $this->assertKeyName($key);
        $key = $this->buildKeyName($key);

        $value = apcu_fetch($key, $success);

        return $success === false ? $default : $value;
    }

    public function set($key, $value, $ttl = null)
    {
        $this->assertKeyName($key);
        $key = $this->buildKeyName($key);

        $ttl = is_null($ttl) ? $this->defaultLifetime : $ttl;

        return apcu_store($key, $value, (int) $ttl);
    }

    public function delete($key)
    {
        $this->assertKeyName($key);
        $key = $this->buildKeyName($key);

        return apcu_delete($key);
    }

    public function clear()
    {
        return apcu_clear_cache();
    }

    public function getMultiple($keys, $default = null)
    {
        $this->assertKeyNames($keys);
        $keys = $this->buildKeyNames($keys);

        $result = apcu_fetch($keys);

        if(!is_null($default) && is_array($result) && count($keys) > count($result))
        {
            $notFoundKeys = array_diff($keys, array_keys($result));
            $result       = array_merge($result, array_fill_keys($notFoundKeys, $default));
        }

        $mappedResult = array();

        foreach($result as $key => $value)
        {
            $key = preg_replace("/^$this->namespace/", '', $key);

            $mappedResult[$key] = $value;
        }

        return $mappedResult;
    }

    public function setMultiple($values, $ttl = null)
    {
        $this->assertKeyNames(array_keys($values));

        $mappedByNamespaceValues = array();

        foreach($values as $key => $value)
        {
            $mappedByNamespaceValues[$this->buildKeyName($key)] = $value;
        }

        $ttl = is_null($ttl) ? $this->defaultLifetime : $ttl;

        $result = apcu_store($mappedByNamespaceValues, (int) $ttl);

        return $result === true ? true : (is_array($result) && count($result) == 0 ? true : false);
    }

    public function deleteMultiple($keys)
    {
        $this->assertKeyNames($keys);
        $keys = $this->buildKeyNames($keys);

        $result = apcu_delete($keys);

        return count($result) === count($keys) ? false : true;
    }

    public function has($key)
    {
        $this->assertKeyName($key);
        $key = $this->buildKeyName($key);

        return (bool) apcu_exists($key);
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
