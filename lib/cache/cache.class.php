<?php
/**
 * The cache library of zentaopms.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@chandao.com>
 * @package     cache
 * @link        https://www.zentao.net
 */
namespace Zentao\Cache;

\helper::import(dirname(__FILE__) . DS . 'simple-cache' . DS . 'CacheInterface.php');
\helper::import(dirname(__FILE__) . DS . 'simple-cache' . DS . 'CacheException.php');
\helper::import(dirname(__FILE__) . DS . 'simple-cache' . DS . 'InvalidArgumentException.php');
\helper::import(dirname(__FILE__) . DS . 'driver' . DS . 'ApcuDriver.php');
\helper::import(dirname(__FILE__) . DS . 'driver' . DS . 'RedisDriver.php');
\helper::import(dirname(__FILE__) . DS . 'driver' . DS . 'YacDriver.php');
\helper::import(dirname(__FILE__) . DS . 'driver' . DS . 'FileDriver.php');

use ZenTao\Cache\SimpleCache\InvalidArgumentException;

class cache
{
    const DRIVER_APCU = 'Apcu';

    const DRIVER_FILE = 'File';

    const DRIVER_YAC = 'Yac';

    const DRIVER_REDIS = 'Redis';

    const DRIVER_LIST = [self::DRIVER_APCU, self::DRIVER_FILE, self::DRIVER_YAC, self::DRIVER_REDIS];

    /**
     * 全局应用程序对象。
     * Global application object.
     *
     * @access private
     * @var object
     */
    private $app;

    /**
     * 全局数据库操作对象。
     * Global database operation object.
     *
     * @access private
     * @var object
     */
    private $dao;

    /**
     * 全局配置对象。
     * Global configuration object.
     *
     * @access private
     * @var object
     */
    private $config;

    /**
     * 全局缓存对象。
     * Global cache object.
     *
     * @access private
     * @var object
     */
    private $cache;

    /**
     * 缓存状态。
     * Cache status.
     *
     * @access private
     * @var string
     */
    private $status = 'enabled';

    /**
     * 缓存命名空间。
     * Cache namespace.
     *
     * @access private
     * @var string
     */
    private $namespace;

    /**
     * 缓存键连接符。
     * Cache key connector.
     *
     * @access private
     * @var string
     */
    private $connector;

    /**
     * 缓存键。
     * Cache key.
     *
     * @access private
     * @var string
     */
    private $key = '';

    /**
     * 缓存标签。
     * Cache label.
     *
     * @access private
     * @var array
     */
    private $labels = [];

    /**
     * 影响缓存的表名。
     * Table name that affects cache.
     *
     * @access private
     * @var string
     */
    private $table = '';

    /**
     * 影响缓存的事件。
     * Event that affects cache.
     *
     * @access private
     * @var string
     */
    private $event = '';

    /**
     * 影响缓存的 WHERE 子句。
     * WHERE clause that affects cache.
     *
     * @access private
     * @var string
     */
    private $where = '';

    /**
     * 受影响的对象列表。
     * Affected object list.
     *
     * @access private
     * @var array
     */
    private $objects = [];

    /**
     * 构造函数，根据配置文件初始化缓存对象。
     * Constructor, initialize cache object according to the configuration file.
     *
     * @param  object $app 全局应用程序对象。
     * @access public
     * @return void
     */
    public function __construct(object $app)
    {
        $this->app    = $app;
        $this->dao    = $app->dao;
        $this->config = $app->config;

        if(empty($this->config->cache->enable)) return $this->log('The cache is not enabled', __FILE__, __LINE__);

        $driver = ucfirst(strtolower($this->config->cache->driver));

        if(!in_array($driver, self::DRIVER_LIST)) return $this->log("Driver {$driver} is not supported.", __FILE__, __LINE__);
        if($driver != self::DRIVER_FILE && !extension_loaded($driver)) return $this->log("Driver ext-{$driver} is not loaded.", __FILE__, __LINE__);

        $connector  = $driver == self::DRIVER_REDIS ? ':' : '-';
        $className  = "ZenTao\Cache\Driver\\{$driver}Driver";
        $scope      = $this->config->cache->scope;
        $namespace  = $this->config->cache->namespace;
        $lifetime   = $this->config->cache->lifetime;
        $redis      = $this->config->redis;

        $this->setNamespace($namespace);
        $this->setConnector($connector);

        if($driver == self::DRIVER_APCU) return $this->cache = new $className($namespace, $lifetime, $scope, $connector);
        if($driver == self::DRIVER_REDIS) return $this->cache = new $className($namespace, $lifetime, $scope, $connector, $redis);
        if($driver == self::DRIVER_YAC) return $this->cache = new $className($namespace, $lifetime);
        if($driver == self::DRIVER_FILE) return $this->cache = new $className($namespace, $lifetime, $app->getCacheRoot());
    }

    /**
     * 设置缓存状态。
     * Set cache status.
     *
     * @param  string $status 缓存状态。enabled: 启用缓存；disabled: 禁用缓存。
     * @access private
     * @return void
     */
    private function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * 设置缓存命名空间。
     * Set cache namespace.
     *
     * @param  string $namespace 缓存命名空间。
     * @access private
     * @return void
     */
    private function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * 设置缓存键连接符。
     * Set cache key connector.
     *
     * @param  string $connector 缓存键连接符。
     * @access private
     * @return void
     */
    private function setConnector(string $connector)
    {
        $this->connector = $connector;
    }

    /**
     * 设置缓存键。
     * Set cache key.
     *
     * @param  string $key 缓存键。
     * @access private
     * @return void
     */
    private function setKey(string $key)
    {
        $this->key = $key;
    }

    /**
     * 设置影响缓存的表名。
     * Set the table name that affects the cache.
     *
     * @param  string $table 表名。
     * @access private
     * @return void
     */
    private function setTable(string $table)
    {
        $this->table = $table;
    }

    /**
     * 设置影响缓存的事件。
     * Set the event that affects the cache.
     *
     * @param  string $event 事件。
     * @access private
     * @return void
     */
    private function setEvent(string $event)
    {
        $this->event = $event;
    }

    /**
     * 设置影响缓存的 WHERE 子句。
     * Set the WHERE clause that affects the cache.
     *
     * @param  string $where WHERE 子句。
     * @access private
     * @return void
     */
    private function setWhere(string $where)
    {
        $this->where = $where;
    }

    /**
     * 设置受影响的对象列表。
     * Set the list of affected objects.
     *
     * @param  array $objects 对象列表。
     * @access private
     * @return void
     */
    private function setObjects(array $objects)
    {
        $this->objects = $objects;
    }

    /**
     * 获取表的主键字段。
     * Get the primary key field of the table.
     *
     * @access private
     * @return string
     */
    public function getTableField(): string
    {
        return $this->config->cache->raw[$this->table];
    }

    /**
     * 获取表的缓存代号。
     * Get the cache code of the table.
     *
     * @access private
     * @return string
     */
    private function getTableCode(): string
    {
        return str_replace(['`', $this->config->db->prefix], '', $this->table);
    }

    /**
     * 获取原始数据类型缓存的键。该缓存用于保存表的原始数据。
     * Get the key of the raw data type cache. This cache is used to save the original data of the table.
     *
     * @param  string $code 缓存代号。
     * @param  int|string $id 主键值。
     * @access private
     * @return string
     */
    private function getRawCacheKey(string $code, int|string $id): string
    {
        return $this->createKey('raw', $code, $id);
    }

    /**
     * 获取集合类型缓存的键。该缓存用于保存表的主键字段。
     * Get the key of the set type cache. This cache is used to save the primary key field of the table.
     *
     * @param  string $code 缓存代号。
     * @access private
     * @return string
     */
    private function getSetCacheKey(string $code): string
    {
        return $this->createKey('set', $code, 'list');
    }

    /**
     * 获取结果类型缓存的键。该缓存用于保存表的计算结果。
     * Get the key of the result type cache. This cache is used to save the calculation results of the table.
     *
     * @param  string $key 缓存键。
     * @access private
     * @return string
     */
    private function getResCacheKey(string $key): string
    {
        $args = explode('_', str_replace('cache', 'res', strtolower($key)));
        return $this->createKey(...$args);
    }

    /**
     * 新增数据时更新缓存。
     * Update cache when adding data.
     *
     * @access private
     * @return void
     */
    private function create()
    {
        $code         = $this->getTableCode();
        $setCacheKey  = $this->getSetCacheKey($code);
        $objectIdList = $this->cache->get($setCacheKey);
        if(!is_array($objectIdList)) return;

        $objectID = $this->dao->lastInsertID();
        if(!$objectID) return $this->log('Failed to fetch last insert id.', __FILE__, __LINE__);

        /* 获取新增的数据。Get the new data. */
        $object = $this->dao->select('*')->from($this->table)->where('id')->eq($objectID)->fetch();
        if(!$object) return $this->log('Failed to fetch new object. The sql is: ' . $this->dao->get(), __FILE__, __LINE__);

        /* 把新增的数据保存到缓存中。Save the new data to cache. */
        $field       = $this->getTableField();
        $rawCacheKey = $this->getRawCacheKey($code, $object->$field);
        $this->cache->set($rawCacheKey, $object);

        /* 把新增的数据的 id 保存到缓存中。Save the id of the new data to cache. */
        $this->cache->set($setCacheKey, $objectIdList ? array_merge($objectIdList, [$object->$field]) : [$object->$field]);

        if(empty($this->config->cache->res[$this->table])) return;

        /* 删除受影响的缓存。Delete the affected cache. */
        $this->deleteAffectedCache([$object]);
    }

    /**
     * 更新数据时更新缓存。
     * Update cache when updating data.
     *
     * @access private
     * @return void
     */
    private function update()
    {
        if(empty($this->objects)) return $this->log('No objects to update.', __FILE__, __LINE__);

        $field = $this->getTableField();
        $code  = $this->getTableCode();

        /* 获取被更新数据的 id 列表。Get the id list of the updated data. */
        $objectIdList = array_map(function($object) use ($field) { return $object->$field; }, $this->objects);

        /* 获取更新后的数据。Get the updated data. */
        $objects = $this->dao->select('*')->from($this->table)->where($field)->in($objectIdList)->fetchAll($field, false);
        if(!$objects) return $this->log('Failed to fetch updated objects. The sql is: ' . $this->dao->get(), __FILE__, __LINE__);

        /* 把更新后的数据保存到缓存中。Save the updated data to cache. */
        $values = [];
        foreach($objects as $object)
        {
            $rawCacheKey = $this->getRawCacheKey($code, $object->$field);
            $values[$rawCacheKey] = $object;
        }
        $this->cache->setMultiple($values);

        if(empty($this->config->cache->res[$this->table])) return;

        /* 获取受影响的数据。Get the affected data. */
        foreach($objectIdList as $objectID)
        {
            if(isset($this->objects[$objectID]) && isset($objects[$objectID]) && $this->objects[$objectID] == $objects[$objectID]) unset($this->objects[$objectID], $objects[$objectID]);
        }
        $affectedObjects = array_merge($this->objects, $objects);

        /* 删除受影响的缓存。Delete the affected cache. */
        $this->deleteAffectedCache($affectedObjects);
    }

    /**
     * 删除数据时更新缓存。
     * Update cache when deleting data.
     *
     * @access private
     * @return void
     */
    private function delete()
    {
        if(empty($this->objects)) return $this->log('No objects to delete.', __FILE__, __LINE__);

        $code         = $this->getTableCode();
        $setCacheKey  = $this->getSetCacheKey($code);
        $objectIdList = $this->cache->get($setCacheKey);
        if(!is_array($objectIdList)) return;

        $field = $this->getTableField();

        /* 把被删除的数据从缓存中删除。Delete the deleted data from cache. */
        $affectedKeys = [];
        foreach($this->objects as $object) $affectedKeys[] = $this->getRawCacheKey($code, $object->$field);
        $this->cache->deleteMultiple($affectedKeys);

        /* 把被删除的数据的 id 从缓存中删除。Delete the id of the deleted data from cache. */
        $this->cache->set($setCacheKey, array_diff($objectIdList, array_map(function($object) use ($field) { return $object->$field; }, $this->objects)));

        if(empty($this->config->cache->res[$this->table])) return;

        /* 删除受影响的缓存。Delete the affected cache. */
        $this->deleteAffectedCache($this->objects);
    }

    /**
     * 删除受影响的缓存。
     * Delete the affected cache.
     *
     * @param  array $objects 受影响的对象列表。
     * @access private
     * @return void
     */
    private function deleteAffectedCache(array $objects)
    {
        /* 根据受影响的数据查找受影响的缓存。Find the affected cache by the affected data. */
        $keys = [];
        foreach($this->config->cache->res[$this->table] as $res)
        {
            $res = (object)$res;

            /* 如果没有设置关联字段则整个缓存都受影响。If no associated fields are set, the entire cache is affected. */
            if(empty($res->fields))
            {
                $keys = $this->getResCacheKey($res->name);
                continue;
            }

            /* 根据关联字段查找受影响的缓存。Find the affected cache by the associated fields. */
            foreach($objects as $object)
            {
                $key = $this->getResCacheKey($res->name);
                foreach($res->fields as $field)
                {
                    if(!isset($object->$field)) return $this->log("Field {$field} does not exist in table {$this->table}.", __FILE__, __LINE__);

                    $key .= $this->connector . $object->$field;
                }
                $keys[] = $key;
            }
        }

        /* 删除受影响的缓存。Delete the affected cache. */
        $this->cache->deleteMultiple($keys);
    }

    /**
     * 检查表是否有缓存设置。
     * Check if the table has cache settings.
     *
     * @param  string $table 表名。
     * @access private
     * @return bool
     */
    private function checkTable(string $table = ''): bool
    {
        if(empty($table)) $table = $this->table;
        return !empty($this->config->cache->raw[$table]);
    }

    /**
     * 记录日志信息。
     * Record log information.
     *
     * @param  string $message 日志信息。
     * @param  string $file    文件名。
     * @param  string $line    行号。
     * @access private
     * @return false
     */
    private function log(string $message, string $file, string $line): bool
    {
        if(!$this->config->debug) return false;

        $runMode = PHP_SAPI == 'cli' ? '_cli' : '';
        $logFile = $this->app->getLogRoot() . 'cache' . $runMode . '.' . date('Ymd') . '.log.php';
        if(!file_exists($logFile)) file_put_contents($logFile, '<?php die(); ?' . ">\n");

        $content = date('Ymd H:i:s') . ': ' . $this->getURI() . "\nError: {$message} in $file on line $line\n";
        file_put_contents($logFile, $content, FILE_APPEND);

        if($this->config->debug >= 2) $this->app->triggerError($message, __FILE__, __LINE__, true);

        return false;
    }

    /**
     * 获取 URI。
     * Get URI.
     *
     * @access private
     * @return string
     */
    private function getURI(): string
    {
        $uri = $this->app->getURI();
        if($uri) return $uri;

        if($this->config->requestType == 'GET') return $_SERVER['REQUEST_URI'];

        if($this->config->requestType == 'PATH_INFO' || $this->config->requestType == 'PATH_INFO2')
        {
            $pathInfo = $this->app->getPathInfo();
            if(empty($pathInfo)) return '';

            $dotPos = strrpos($pathInfo, '.');
            if($dotPos) return substr($pathInfo, 0, $dotPos);
            return $pathInfo;
        }

        return '';
    }

    /**
     * 初始化指定表的缓存。
     * Initialize the cache of the specified table.
     *
     * @access private
     * @return array
     */
    private function initTableCache(): array
    {
        $field   = $this->getTableField();
        $objects = $this->dao->select('*')->from($this->table)->fetchAll($field, false);
        if(!$objects) return [];

        $values = [];
        $code   = $this->getTableCode();
        foreach($objects as $key => $object)
        {
            $rawCacheKey = $this->getRawCacheKey($code, $key);
            $values[$rawCacheKey] = $object;
        }

        $this->cache->setMultiple($values);

        $setCacheKey = $this->getSetCacheKey($code);
        $this->cache->set($setCacheKey, array_keys($objects));

        return $objects;
    }

    /**
     * 生成缓存键。
     * Generate cache key.
     *
     * @param  mixed  ...$args 缓存键的参数。
     * @access public
     * @return string
     */
    public function createKey(...$args): string
    {
        return $this->namespace . $this->connector . implode($this->connector, $args);
    }

    /**
     * 从缓存中获取指定表指定 id 的数据。
     * Get the data of the specified table and id from the cache.
     *
     * @param  string $table 表名。
     * @param  int|string $id 主键值。
     * @access public
     * @return object|false
     */
    public function fetch(string $table, int|string $id): object|null
    {
        if($this->status == 'disabled') return null;
        if(!$this->checkTable($table)) return $this->log("Table {$table} is not set in the cache configuration", __FILE__, __LINE__);

        if(empty($table)) return $this->log('The table name is empty', __FILE__, __LINE__);
        if(empty($id))    return $this->log('The id is empty', __FILE__, __LINE__);

        $this->setTable($table);

        $code   = $this->getTableCode();
        $key    = $this->getRawCacheKey($code, $id);
        $object = $this->cache->get($key);
        if($object) return $object;

        $objects = $this->initTableCache();
        return isset($objects[$id]) ? $objects[$id] : null;
    }

    /**
     * 从缓存中获取指定表的所有数据。
     * Get all data of the specified table from the cache.
     *
     * @param  string $table 表名。
     * @param  array  $objectIdList 主键值列表。
     * @access public
     * @return array
     */
    public function fetchAll(string $table, array $objectIdList = []): array
    {
        if($this->status == 'disabled') return [];
        if(!$this->checkTable($table)) return $this->log("Table {$table} is not set in the cache configuration", __FILE__, __LINE__);

        if(empty($table)) return $this->log('The table name is empty', __FILE__, __LINE__);

        $this->setTable($table);

        $code = $this->getTableCode();

        /* 尝试获取指定表的所有主键字段的值。Try to get the values of all primary key fields of the specified table. */
        $setCacheKey     = $this->getSetCacheKey($code);
        $allObjectIdList = $this->cache->get($setCacheKey);

        /* 如果主键字段的值为空，则初始化指定表的缓存。If the value of the primary key field is empty, initialize the cache of the specified table. */
        if(!$allObjectIdList)
        {
            $allData = $this->initTableCache();
            if(empty($objectIdList)) return $allData;

            return array_intersect_key($allData, $objectIdList);
        }

        /* 如果主键字段的值不为空，则从缓存中获取数据。If the value of the primary key field is not empty, get the data from the cache. */
        if(empty($objectIdList)) $objectIdList = $allObjectIdList;

        $keys = [];
        foreach($objectIdList as $objectID)
        {
            if(!$objectID) continue;
            $keys[$objectID] = $this->getRawCacheKey($code, $objectID);
        }

        $objects = $this->cache->getMultiple(array_values($keys));

        /* 如果缓存中没有全部的数据，则从数据库中获取缺失的数据。If not all data in cache, get the missing data from the database. */
        if(count($keys) > count($objects))
        {
            $lostObjects = [];
            $diffIdList  = array_keys(array_diff($keys, array_keys($objects)));
            $diffObjects = $this->dao->select('*')->from($table)->where('id')->in($diffIdList)->fetchAll('', false);
            foreach($diffObjects as $object)
            {
                $rawCacheKey = $this->getRawCacheKey($code, $object->id);
                $lostObjects[$rawCacheKey] = $object;
            }

            /* 把缺失的数据保存到缓存中。Save the missing data to cache. */
            $this->cache->setMultiple($lostObjects);

            $objects += $lostObjects;
        }

        if(!$objects) return [];

        $result = [];
        $field  = $this->getTableField();
        foreach($objects as $object) $result[$object->$field] = $object;

        return $result;
    }

    /**
     * 设置缓存键。
     * Set cache key.
     *
     * @param  string $key 缓存键。
     * @param  mixed  ...$args 缓存键的参数。
     * @access public
     * @return object
     */
    public function key($key, ...$args)
    {
        if(empty($this->config->cache->keys[$key])) return $this->log("Key {$key} is not defined", __FILE__, __LINE__);

        $cache = $this->config->cache->keys[$key];
        if(!empty($cache->fields) && !empty($args))
        {
            $tableFields = $this->dao->descTable($cache->table);
            foreach($cache->fields as $index => $field)
            {
                if(!isset($tableFields[$field])) return $this->log("Field {$field} does not exist in table {$cache->table}", __FILE__, __LINE__);
                if(!isset($args[$index])) continue;

                $tableField = $tableFields[$field];
                if(stripos($tableField->type, 'int')     !== false) $args[$index] = (int)  $args[$index];
                if(stripos($tableField->type, 'float')   !== false) $args[$index] = (float)$args[$index];
                if(stripos($tableField->type, 'decimal') !== false) $args[$index] = (float)$args[$index];
                if(stripos($tableField->type, 'double')  !== false) $args[$index] = (float)$args[$index];
            }
        }

        $key = $this->getResCacheKey(constant($key));
        foreach($args as $arg) $key .= $this->connector . $arg;

        $this->setKey($key);
        return $this;
    }

    /**
     * 设置缓存标签。
     * Set cache label.
     *
     * @param  string $label 缓存标签。
     * @access public
     * @return object
     */
    public function label(string $label)
    {
        if(empty($label)) return $this->log('The label is empty', __FILE__, __LINE__);
        if(empty($this->key)) return $this->log('The key is empty', __FILE__, __LINE__);
        if(isset($this->labels[$label])) return $this->log("Label {$label} already used", __FILE__, __LINE__);
        $this->labels[$label] = $this->key;
        return $this;
    }

    /**
     * 根据当前缓存键获取缓存。
     * Get cache according to the current cache key.
     *
     * @access public
     * @return mixed
     */
    public function get()
    {
        if($this->status == 'disabled') return null;
        if(empty($this->key)) return $this->log('The key is empty', __FILE__, __LINE__);
        return $this->cache->get($this->key);
    }

    /**
     * 根据指定缓存键获取缓存。
     * Get cache according to the specified cache key.
     *
     * @param  string $key 缓存键。
     * @access public
     * @return mixed
     */
    public function getByKey(string $key)
    {
        if($this->status == 'disabled') return null;
        if(empty($key)) return $this->log('The key is empty', __FILE__, __LINE__);

        return $this->cache->get($key);
    }

    /**
     * 根据当前缓存键保存缓存。
     * Save cache according to the current cache key.
     *
     * @param  mixed $value 缓存值。
     * @access public
     * @return bool
     */
    public function save($value)
    {
        if($this->status == 'disabled') return false;
        if(empty($this->key)) return $this->log('The key is empty', __FILE__, __LINE__);
        return $this->cache->set($this->key, $value);
    }

    /**
     * 根据指定缓存建保存缓存。
     * Save cache according to the specified cache key.
     *
     * @param  string $key   缓存键。
     * @param  mixed  $value 缓存值。
     * @param  int    $ttl   缓存时间。
     * @access public
     * @return bool
     */
    public function saveByKey(string $key, $value, int $ttl = 0)
    {
        if($this->status == 'disabled') return false;
        if(empty($key)) return $this->log('The key is empty', __FILE__, __LINE__);

        return $this->cache->set($key, $value, $ttl);
    }

    /**
     * 根据指定缓存标签保存缓存。
     * Save cache according to the specified cache label.
     *
     * @param  string $label 缓存标签。
     * @param  mixed  $value 缓存值。
     * @access public
     * @return bool
     */
    public function saveByLabel(string $label, $value)
    {
        if($this->status == 'disabled') return false;
        if(empty($label)) return $this->log('The label is empty', __FILE__, __LINE__);
        if(empty($this->labels[$label])) return $this->log("Label {$label} does not exist", __FILE__, __LINE__);

        return $this->cache->set($this->labels[$label], $value);
    }

    /**
     * 重置缓存相关设置项。
     * Reset cache related settings.
     *
     * @access public
     * @return void
     */
    public function reset()
    {
        $this->setKey('');
        $this->setTable('');
        $this->setEvent('');
        $this->setWhere('');
        $this->setObjects([]);
    }

    /**
     * 执行数据库操作前准备更新缓存需要的设置项。
     * Prepare the settings required to update the cache before executing the database operation.
     *
     * @param  string $table 表名。
     * @param  string $event 事件。
     * @param  string $sql   SQL 语句。
     * @access public
     * @return void
     */
    public function prepare(string $table, string $event, string $sql)
    {
        $this->reset();

        if($this->status == 'disabled') return;
        if(!$this->checkTable($table)) return;

        if(empty($table)) return $this->log('Table name is required.', __FILE__, __LINE__);
        if(empty($event)) return $this->log('Event type is required.', __FILE__, __LINE__);
        if(empty($sql))   return $this->log('SQL statement is required.', __FILE__, __LINE__);

        $this->setTable($table);
        $this->setEvent($event);

        if($event == 'update' || $event == 'delete')
        {
            /* 获取 WHERE 子句的内容。Get the content of WHERE clause. */
            $whereLen  = strlen(\DAO::WHERE);
            $wherePOS  = strrpos($sql, \DAO::WHERE);
            $groupPOS  = strrpos($sql, \DAO::GROUPBY);
            $havingPOS = strrpos($sql, \DAO::HAVING);
            $orderPOS  = strrpos($sql, \DAO::ORDERBY);
            $limitPOS  = strrpos($sql, \DAO::LIMIT);
            $splitPOS  = $orderPOS  ? $orderPOS  : $limitPOS;
            $splitPOS  = $havingPOS ? $havingPOS : $splitPOS;
            $splitPOS  = $groupPOS  ? $groupPOS  : $splitPOS;

            $where = '';
            if($wherePOS)
            {
                if($splitPOS)
                {
                    $where = substr($sql, $wherePOS + $whereLen, $splitPOS - $wherePOS - $whereLen);
                }
                else
                {
                    $where = substr($sql, $wherePOS + $whereLen);
                }
            }

            /* 执行操作后数据已经被修改，所以需要提前获取被影响的数据。*/
            $field   = $this->getTableField();
            $objects = $this->dao->select('*')->from($table)->beginIF($where)->where($where)->fi()->fetchAll($field, false);

            $this->setWhere($where);
            $this->setObjects($objects);
        }
    }

    /**
     * 执行数据库操作后更新缓存。
     * Update the cache after executing the database operation.
     *
     * @access public
     * @return void
     */
    public function sync()
    {
        if($this->status == 'disabled') return;
        if(!$this->checkTable()) return;

        if(empty($this->table)) return $this->log('Table name is required.', __FILE__, __LINE__);
        if(empty($this->event)) return $this->log('Event type is required.', __FILE__, __LINE__);

        if($this->event == 'insert') $this->create();
        if($this->event == 'update') $this->update();
        if($this->event == 'delete') $this->delete();

        $this->reset();
    }

    /**
     * 清空缓存。
     * Clear cache.
     *
     * @access public
     * @return void
     */
    public function clear()
    {
        $this->setStatus('disabled');
        $this->cache->clear();
        $this->setStatus('normal');
    }

    /**
     * 关闭缓存连接。
     * Close cache connection.
     *
     * @access public
     * @return void
     */
    public function close()
    {
        if(method_exists($this->cache, 'close')) $this->cache->close();
    }

    /**
     * 获取内存使用情况。
     * Get memory usage.
     *
     * @param  string $type
     * @return string
     */
    public function memory(string $type)
    {
        return $this->cache->memory($type);
    }
}
