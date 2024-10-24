<?php
declare(strict_types=1);
/**
 * The model file of cache module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@chandao.com>
 * @package     cache
 * @link        https://www.zentao.net
 */
class cacheModel extends model
{
    /**
     * 缓存的模块。
     * The module of cache.
     *
     * @access private
     * @var    string
     */
    private $module = '';

    /**
     * 缓存的方法。
     * The method of cache.
     *
     * @access private
     * @var    string
     */
    private $method = ''

    /**
     * 缓存的键。
     * The key of cache.
     *
     * @access private
     * @var    string
     */
    private $key = '';

    /**
     * 缓存的值。
     * The value of cache.
     *
     * @access private
     * @var    int|string|object|array
     */
    private $value = '';

    /**
     * 缓存相关的键。
     * The related keys of cache.
     *
     * @access private
     * @var    array
     */
    private $relatedKeys = [];

    /**
     * 构造方法。
     * Constructor.
     *
     * @access public
     * @return void
     */
    public function __construct(): void
    {
        parent::__construct();

        $this->cache = $this->app->loadClass('cache');
    }

    /**
     * 魔术方法，加载子类。
     * Magic get method.
     *
     * @param  string $module
     * @access public
     * @return cacheModel
     */
    public function __get(string $module): cacheModel
    {
        $this->reset();

        if(!isset($this->config->cache->keys[$module])) return $this->log("The cache configuration of the module {$module} is not exist.");

        $this->setModule($module);

        if(isset($this->$module)) return $this;

        $file = dirname(__FILE__) . DS . 'handler' . DS . strtolower($module) . '.php';
        if(!helper::import($file)) return $this->log("The handler file {$file} is not exist.");

        $class = $module . 'Handler';
        $this->$module = new $class();

        return $this;
    }

    /**
     * 魔术方法，调用子类方法。
     * Magic call method.
     *
     * @param  string $method
     * @param  array  $args
     * @access public
     * @return cacheModel
     */
    public function __call(string $method, array $args): cacheModel
    {
        if(!isset($this->config->cache->keys[$this->module])) return $this->log('The module is not exist.');

        $this->setMethod($method);

        $key = "res:{$this->module}:{$method}";
        $this->setKey($key);

        if(empty($this->$module)) return $this;
        if(!method_exists($this->$module, $method)) return $this->log("The method {$method} is not exist.");

        $key = call_user_func_array([$this->$module, $method], $args);
        $this->setKey($key);

        return $this;
    }

    /**
     * 设置缓存的模块。
     * Set the module of cache.
     *
     * @param  string $module
     * @access private
     * @return void
     */
    private function setModule(string $module): void
    {
        $this->module = $module;
    }

    /**
     * 设置缓存的方法。
     * Set the method of cache.
     *
     * @param  string $method
     * @access private
     * @return void
     */
    private function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     * 设置缓存的键。
     * Set the key of cache.
     *
     * @param  string $key
     * @access private
     * @return void
     */
    private function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * 设置缓存的值。
     * Set the value of cache.
     *
     * @param  int|string|object|array $value
     * @access private
     * @return void
     */
    private function setValue(int|string|object|array $value): void
    {
        $this->value = $value;
    }

    /**
     * 设置缓存相关的键。
     * Set the related keys of cache.
     *
     * @param  array $relatedKeys
     * @access private
     * @return void
     */
    private function setRelatedKeys(array $relatedKeys): void
    {
        $this->relatedKeys = $relatedKeys;
    }

    /**
     * 重置缓存相关设置。
     * Reset the cache settings.
     *
     * @access private
     * @return void
     */
    private function reset(): void
    {
        $this->setModule('');
        $this->setMethod('');
        $this->setKey('');
        $this->setValue('');
        $this->setRelatedKeys([]);
    }

    /**
     * 设置缓存的值。
     * Set the value of cache.
     *
     * @param  int|string|object|array $value
     * @access public
     * @return cacheModel
     */
    public function value(int|string|object|array $value): cacheModel
    {
        if(empty($this->config->cache->keys[$this->module][$this->method]['type'])) return $this->log("The cache configuration of [{$this->module}][{$this->method}] is not exist.");

        $config = $this->config->cache->keys[$this->module][$this->method];
        $type   = $config['type'];
        $filter = isset($config['filter']) ? $config['filter'] : '';

        if($type == 'int'    && !is_int($value))    return $this->log('The value must be int.');
        if($type == 'string' && !is_string($value)) return $this->log('The value must be string.');
        if($type == 'object' && !is_object($value)) return $this->log('The value must be object.');
        if($type == 'array')
        {
            if(!is_array($value)) return $this->log('The value must be array.');
            if($filter != 'encode' && !$this->isIndexedArray($array)) return $this->log('The value must be indexed array.');
        }

        if($type == 'object' || ($type == 'array' && $filter == 'encode')) $value = json_encode($value);

        $this->setValue($value);

        return $this;
    }

    /**
     * 设置缓存相关的键。
     * Set the related keys of cache.
     *
     * @param  array $relatedKeys
     * @access public
     * @return cacheModel
     */
    public function relatedKeys(array $relatedKeys): cacheModel
    {
        if(!is_array($relatedKeys)) return $this->log('The relatedKeys must be array.');
        if(!$this->isIndexedArray($relatedKeys)) return $this->log('The relatedKeys must be indexed array.');

        $this->setRelatedKeys($relatedKeys);
        return $this;
    }

    /**
     * 获取缓存。
     * Fetch the cache.
     *
     * @access public
     * @return false|int|string|object|array
     */
    public function fetch(): bool|int|string|object|array
    {
        if(empty($this->config->cache->keys[$this->module][$this->method]['type']))
        {
            $this->log("The cache configuration of [{$this->module}][{$this->method}] is not exist.");
            return false;
        }

        $config = $this->config->cache->keys[$this->module][$this->method];
        $type   = $config['type'];
        $filter = isset($config['filter']) ? $config['filter'] : '';

        if($type == 'int')    return (int)$this->app->redis->get($this->key);
        if($type == 'string') return $this->app->redis->get($this->key);
        if($type == 'object') return json_decode($this->app->redis->get($this->key));
        if($type == 'array')
        {
            if($filter == 'encode') return json_decode($this->app->redis->get($this->key));

            return $this->app->redis->smembers($this->key);
        }
        return false;
    }

    /**
     * 保存缓存。
     * Save the cache.
     *
     * @access public
     * @return false|int|string|object|array
     */
    public function save(): bool|int|string|object|array
    {
        if(empty($this->value))
        {
            $this->log('The value is empty.');
            return $this->result(false);
        }
        if(empty($this->config->cache->keys[$this->module][$this->method]['type']))
        {
            $this->log("The cache configuration of [{$this->module}][{$this->method}] is not exist.");
            return $this->result(false);
        }

        $config = $this->config->cache->keys[$this->module][$this->method];
        $type   = $config['type'];
        $filter = isset($config['filter']) ? $config['filter'] : '';

        if($type == 'int')    return $this->result($this->app->redis->set($this->key, $this->value));
        if($type == 'string') return $this->result($this->app->redis->set($this->key, $this->value));
        if($type == 'object') return $this->result($this->app->redis->set($this->key, $this->value));
        if($type == 'array')
        {
            if($filter == 'encode') return $this->result($this->app->redis->set($this->key, $this->value));

            return return $this->result($this->app->redis->sadd($this->key, ...$this->value));
        }
        return $this->result(false);
    }

    /**
     * 返回结果。
     * Return the result.
     *
     * @param  bool|int|string|object|array $result
     * @access private
     * @return bool|int|string|object|array
     */
    private function result(bool|int|string|object|array $result): bool|int|string|object|array
    {
        if($result) $this->updateRelated();
        $this->reset();
        return $result;
    }

    /**
     * 更新相关的缓存。
     * Update the related cache.
     *
     * @access private
     * @return bool
     */
    private function updateRelatedKeys(): bool
    {
        if(empty($this->config->objectTables[$this->module])) return false;

        $pairs  = [];
        $table  = $this->config->objectTables[$this->module];
        $code   = str_replace(['`', $this->config->db->prefix], '', $table);
        $object = $this->app->redis->fetchAll($table, $this->relatedKeys);
        $key    = $this->config->redis->caches[$table];
        foreach($objects as $object)
        {
            $relatedKeys = isset($object->_relatedKeys) ? $object->_relatedKeys : [];
            $relatedKeys[] = $this->key;
            $object->_relatedKeys = array_unique($relatedKeys);
            $pairs["raw:{$code}:{$object->$key}"] = json_encode($object, JSON_UNESCAPED_UNICODE);
        }

        $this->app->redis->mset($pairs);

        return true;
    }

    /**
     * 检查是否为索引数组。
     * Check if it is an indexed array.
     *
     * @param  array $array
     * @access private
     * @return bool
     */
    private function isIndexedArray(array $array): bool
    {
        if(function_exists('array_is_list')) return array_is_list($value);
        return $value === array_values($value);
    }

    /**
     * 记录日志。
     * Save log.
     *
     * @param  string $message
     * @access private
     * @return object
     */
    private function log(string $message)
    {
        if($this->config->debug) $this->app->triggerError($message, __FILE__, __LINE__, $this->config->debug >= 2);
        return $this;
    }
}
