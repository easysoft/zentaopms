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
    private $method = '';

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
    public function __construct()
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
    public function __call($method, $args)
    {
        $this->setMethod($method);

        $key = "res:{$this->module}:{$method}";
        $this->setKey($key);

        if(empty($this->{$this->module})) return $this;
        if(!method_exists($this->{$this->module}, $method)) return $this->log("The method {$method} is not exist.");

        $key = call_user_func_array([$this->{$this->module}, $method], $args);
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
        $this->setValue(json_encode($value));
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
        $result = $this->app->redis->get($this->key);
        if($result) return json_decode($result);
        return $result;
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
            $this->reset();
            return false;
        }

        $this->app->redis->set($this->key, $this->value);
        $this->updateRelatedKeys();
        $this->reset();
        return true;
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
