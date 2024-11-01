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
helper::import(dirname(__FILE__) . DS . 'simple-cache' . DS . 'CacheInterface.php');
helper::import(dirname(__FILE__) . DS . 'simple-cache' . DS . 'CacheException.php');
helper::import(dirname(__FILE__) . DS . 'simple-cache' . DS . 'InvalidArgumentException.php');
helper::import(dirname(__FILE__) . DS . 'driver' . DS . 'ApcuDriver.php');
helper::import(dirname(__FILE__) . DS . 'driver' . DS . 'RedisDriver.php');
helper::import(dirname(__FILE__) . DS . 'driver' . DS . 'YacDriver.php');
helper::import(dirname(__FILE__) . DS . 'driver' . DS . 'FileDriver.php');

use ZenTao\Cache\SimpleCache\InvalidArgumentException;

class cache
{
    const DRIVER_APCU = 'Apcu';

    const DRIVER_FILE = 'File';

    const DRIVER_YAC = 'Yac';

    const DRIVER_REDIS = 'Redis';

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
        switch($driver)
        {
            case self::DRIVER_APCU:
                $className = 'ZenTao\Cache\Driver\ApcuDriver';
                break;
            case self::DRIVER_YAC:
                $className = 'ZenTao\Cache\Driver\YacDriver';
                break;
            case self::DRIVER_FILE:
                $className = 'ZenTao\Cache\Driver\FileDriver';
                break;
            case self::DRIVER_REDIS:
                $className = 'ZenTao\Cache\Driver\RedisDriver';
                break;
            default:
                return $this->log("Driver {$driver} is not supported.", __FILE__, __LINE__);
        }

        if($driver != self::DRIVER_FILE && !extension_loaded($driver)) return $this->log("Driver ext-{$driver} is not loaded.", __FILE__, __LINE__);

        $this->cache = new $className($this->config->db->name, $this->config->cache->lifetime, $app->getCacheRoot());
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
    private function getTableField(): string
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
     * 获取缓存键。
     * Get cache key.
     *
     * @param  string $key 缓存键。
     * @access private
     * @return string
     */
    private function getCacheKey(string $key): string
    {
        return str_replace(['cache', '_'], ['res', ':'], strtolower($key));
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
        $objectID = $this->dao->lastInsertID();
        if(!$objectID) return $this->log('Failed to fetch last insert id.', __FILE__, __LINE__);

        $field = $this->getTableField();
        $code  = $this->getTableCode();

        /* 获取新增的数据。Get the new data. */
        $object = $this->dao->select('*')->from($this->table)->where('id')->eq($objectID)->fetch();
        if(!$object) return $this->log('Failed to fetch the new object. The sql is: ' . $this->dao->get(), __FILE__, __LINE__);

        /* 把新增的数据保存到缓存中。Save the new data to cache. */
        $this->cache->set("raw:{$code}:{$object->$field}", $object);

        /* 把新增的数据的 id 保存到缓存中。Save the id of the new data to cache. */
        $objectIdList = $this->cache->get("set:{$code}List");
        $this->cache->set("set:{$code}List", $objectIdList ? array_merge($objectIdList, [$object->$field]) : [$object->$field]);

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
        $objects = $this->dao->select('*')->from($this->table)->where($field)->in($objectIdList)->fetchAll($field);
        if(!$objects) return $this->log('Failed to fetch updated objects. The sql is: ' . $this->dao->get(), __FILE__, __LINE__);

        /* 把更新后的数据保存到缓存中。Save the updated data to cache. */
        $values = [];
        foreach($objects as $object) $values["raw:{$code}:{$object->$field}"] = $object;
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

        $field = $this->getTableField();
        $code  = $this->getTableCode();

        /* 把被删除的数据从缓存中删除。Delete the deleted data from cache. */
        $keys = [];
        foreach($this->objects as $object) $keys[] = "raw:{$code}:{$object->key}";
        $this->cache->deleteMultiple($keys);

        /* 把被删除的数据的 id 从缓存中删除。Delete the id of the deleted data from cache. */
        $objectIdList = $this->cache->get("set:{$code}List");
        $this->cache->set("set:{$code}List", array_diff($objectIdList, array_map(function($object) use ($field) { return $object->$field; }, $this->objects)));

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
                $keys[] = $this->getCacheKey($res->name);
                continue;
            }

            /* 根据关联字段查找受影响的缓存。Find the affected cache by the associated fields. */
            foreach($objects as $object)
            {
                $key = $this->getCacheKey($res->name);
                foreach($res->fields as $field)
                {
                    if(!isset($object->$field)) return $this->log("The {$field} field does not exist in table {$this->table}.", __FILE__, __LINE__);

                    $key .= ':' . $object->$field;
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
        $logFile = $this->app->getLogRoot() . 'redis' . $runMode . '.' . date('Ymd') . '.log.php';
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
        $objects = $this->dao->select('*')->from($this->table)->fetchAll($field);
        if(!$objects) return [];

        $values = [];
        $code   = $this->getTableCode();
        foreach($objects as $key => $object) $values["raw:{$code}:{$key}"] = $object;

        $this->cache->setMultiple($values);
        $this->cache->set("set:{$code}List", array_keys($objects));

        return $objects;
    }

    /**
     * 从缓存中获取指定表的所有数据。
     * Get all data of the specified table from the cache.
     *
     * @param  string $table 表名。
     * @access public
     * @return array
     */
    public function fetchAll(string $table): array
    {
        if(!$this->checkTable($table)) return [];

        if(empty($table)) return $this->log('The table name is empty', __FILE__, __LINE__);

        $this->setTable($table);

        $code         = $this->getTableCode();
        $objectIdList = $this->cache->get("set:{$code}List");
        if(!$objectIdList) return $this->initTableCache();

        $keys = [];
        foreach($objectIdList as $objectID) $keys[] = "raw:{$code}:{$objectID}";

        $objects = $this->cache->getMultiple($keys);
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
        if(empty($this->config->cache->keys[$key])) return $this->log("The {$key} key is not defined", __FILE__, __LINE__);

        $cache = $this->config->cache->keys[$key];
        if(!empty($cache->fields) && !empty($args))
        {
            $tableFields = $this->dao->descTable($cache->table);
            foreach($cache->fields as $index => $field)
            {
                if(!isset($tableFields[$field])) return $this->log("The {$field} field does not exist in table {$cache->table}", __FILE__, __LINE__);
                if(!isset($args[$index])) continue;

                $tableField = $tableFields[$field];
                if(stripos($tableField->type, 'int')     !== false) $args[$index] = (int)  $args[$index];
                if(stripos($tableField->type, 'float')   !== false) $args[$index] = (float)$args[$index];
                if(stripos($tableField->type, 'decimal') !== false) $args[$index] = (float)$args[$index];
                if(stripos($tableField->type, 'double')  !== false) $args[$index] = (float)$args[$index];
            }
        }

        $key = $this->getCacheKey(constant($key));
        foreach($args as $arg) $key .= ':' . $arg;

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
        if(empty($this->key)) return $this->log('The key is empty', __FILE__, __LINE__);
        return $this->cache->set($this->key, $value);
    }

    /**
     * 根据指定缓存建保存缓存。
     * Save cache according to the specified cache key.
     *
     * @param  string $key   缓存键。
     * @param  mixed  $value 缓存值。
     * @access public
     * @return bool
     */
    public function saveByKey(string $key, $value)
    {
        if(empty($key)) return $this->log('The key is empty', __FILE__, __LINE__);
        return $this->cache->set($key, $value);
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
        if(empty($label)) return $this->log('The label is empty', __FILE__, __LINE__);
        if(empty($this->labels[$label])) return $this->log("The {$label} label is not set", __FILE__, __LINE__);

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

        if(!$this->checkTable($table)) return;

        if(empty($table)) return $this->log('Table name is required.', __FILE__, __LINE__);
        if(empty($event)) return $this->log('Event type is required.', __FILE__, __LINE__);
        if(empty($sql))   return $this->log('SQL statement is required.', __FILE__, __LINE__);

        $this->setTable($table);
        $this->setEvent($event);

        if($event == 'update' || $event == 'delete')
        {
            /* 获取 WHERE 子句的内容。Get the content of WHERE clause. */
            $whereLen  = strlen(DAO::WHERE);
            $wherePOS  = strrpos($sql, DAO::WHERE);
            $groupPOS  = strrpos($sql, DAO::GROUPBY);
            $havingPOS = strrpos($sql, DAO::HAVING);
            $orderPOS  = strrpos($sql, DAO::ORDERBY);
            $limitPOS  = strrpos($sql, DAO::LIMIT);
            $splitPOS  = $orderPOS  ? $orderPOS  : $limitPOS;
            $splitPOS  = $havingPOS ? $havingPOS : $splitPOS;
            $splitPOS  = $groupPOS  ? $groupPOS  : $splitPOS;
            if($splitPOS)
            {
                $where = substr($sql, $wherePOS + $whereLen, $splitPOS - $wherePOS - $whereLen);
            }
            else
            {
                $where = substr($sql, $wherePOS + $whereLen);
            }

            /* 执行操作后数据已经被修改，所以需要提前获取被影响的数据。*/
            $field   = $this->getTableField();
            $objects = $this->dao->select('*')->from($table)->beginIF($where)->where($where)->fi()->fetchAll($field);
            if(!$objects) return $this->log('Failed to fetch affected objects. The sql is: ' . $this->dao->get(), __FILE__, __LINE__);

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
        if(!$this->checkTable()) return;

        if(empty($this->table)) return $this->log('Table name is required.', __FILE__, __LINE__);
        if(empty($this->event)) return $this->log('Event type is required.', __FILE__, __LINE__);

        if($this->event == 'insert') $this->create();
        if($this->event == 'update') $this->update();
        if($this->event == 'delete') $this->delete();

        $this->reset();
    }
}
