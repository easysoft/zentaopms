<?php
declare(strict_types=1);
/**
 * The zredis library of zentaopms.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@chandao.com>
 * @package     zredis
 * @link        https://www.zentao.net
 */
class zredis
{
    /**
     * 同步标识。
     * Synchronization flag.
     *
     * @var string
     * @access public
     */
    const SYNCHRONIZED = 'sys:synchronized';

    /**
     * 全局对象 $app。
     * The global $app object.
     *
     * @var object
     * @access private
     */
    private $app;

    /**
     * 全局 $config 对象。
     * The global $config object.
     *
     * @var object
     * @access private
     */
    private $config;

    /**
     * $dao 对象，用于访问或者更新数据库。
     * The $dao object, used to access or update database.
     *
     * @var dao
     * @access private
     */
    private $dao;

    /**
     * $redis 对象，用于访问或者更新 Redis。
     * The $redis object, used to access or update database.
     *
     * @var redis
     * @access private
     */
    private $redis = null;

    /**
     * 触发缓存更新的表名。
     * The table name to trigger cache update.
     *
     * @var string
     * @access private
     */
    private $table = '';

    /**
     * 触发缓存更新的事件类型，包括 insert, update, delete。
     * The event type to trigger cache update, including insert, update, delete.
     *
     * @var string
     * @access private
     */
    private $event = '';

    /**
     * 触发缓存更新的条件。
     * The condition to trigger cache update.
     *
     * @var string
     * @access private
     */
    private $condition = '';

    /**
     * 触发缓存删除的键列表。实际存储的值是根据缓存的 key 字段来决定的。
     * The ID list to trigger cache delete. The actual stored value is determined by the key field of the cache.
     *
     * @var array
     * @access private
     */
    private $keyList = [];

    /**
     * 连接 Redis 服务器并初始化缓存。
     * Connect to Redis server and initialize cache.
     *
     * @param  object $dao
     * @access public
     * @return void
     */
    public function __construct(object $app)
    {
        global $config;

        $this->app    = $app;
        $this->config = $config;
        $this->dao    = $app->dao;

        if(empty($this->config->redis->enable)) helper::end('Redis is not enabled in the configuration file.');

        try
        {
            $this->redis = new Redis();

            $version = phpversion('redis');
            if(version_compare($version, '5.3.0', 'ge'))
            {
                $this->redis->connect($this->config->redis->host , $this->config->redis->port, $this->config->redis->timeout, '', 0, 0, ['auth' => [$this->config->redis->username, $this->config->redis->password]]);
            }
            else
            {
                $this->redis->connect($this->config->redis->host , $this->config->redis->port, $this->config->redis->timeout, '', 0, 0);
                $this->redis->auth(['pass' => $this->config->redis->password]);
            }

            if(!$this->redis->ping()) helper::end('Can not connect to Redis server.');

            return $this;
        }
        catch(RedisException $e)
        {
            $this->triggerError($e->getMessage(), __FILE__, __LINE__, true);
        }
    }

    /**
     * 同步缓存前准备相关参数。
     * Prepare related parameters before synchronizing cache.
     *
     * @param  string $table
     * @param  string $event
     * @param  string $sql
     * @access public
     * @return void
     */
    public function prepare(string $table, string $event, string $sql)
    {
        if(empty($this->redis))                             return $this->log('Redis is not initialized.');
        if(empty($table))                                   return $this->log('Table name is required.');
        if(empty($event))                                   return $this->log('Event type is required.');
        if(empty($sql))                                     return $this->log('SQL is required.');
        if(empty($this->config->redis->cache->raw[$table])) return $this->log('No cache settings for table ' . $table);

        $this->table = $table;
        $this->event = $event;

        $condition = '';
        $keyList   = [];
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
                $condition = substr($sql, $wherePOS + $whereLen, $splitPOS - $wherePOS - $whereLen);
            }
            else
            {
                $condition = substr($sql, $wherePOS + $whereLen);
            }

            if($event == 'delete')
            {
                /* 执行删除操作后数据已经被删除，所以需要提前获取 ID 列表。*/
                $field   = $this->config->redis->cache->raw[$table];
                $keyList = $this->dao->select($field)->from($table)->beginIF($condition)->where($condition)->fi()->fetchPairs();
            }
        }

        $this->condition = $condition;
        $this->keyList   = array_values($keyList);
    }

    /**
     * 同步缓存。
     * Sync cache.
     *
     * @access public
     * @return void
     */
    public function sync()
    {
        if(empty($this->redis))                                   return $this->log('Redis is not initialized.');
        if(empty($this->table))                                   return $this->log('Table name is required.');
        if(empty($this->event))                                   return $this->log('Event type is required.');
        if(empty($this->config->redis->cache->raw[$this->table])) return $this->log('No cache settings for table ' . $this->table);

        $this->redis->multi();

        if($this->event == 'insert') $this->create();
        if($this->event == 'update') $this->update();
        if($this->event == 'delete') $this->delete();

        $this->redis->set(zredis::SYNCHRONIZED, date('Y-m-d H:i:s'));

        $this->redis->exec();

        $this->reset();
    }

    /**
     * 根据key获取值。
     * Get value by key.
     *
     * @param  string $key
     * @access public
     * @return mixed
     */
    public function get(string $key)
    {
        $value = $this->redis->get($key);
        if(!$value) return $value;

        return json_decode($value);
    }

    /**
     * 设置缓存。
     * Set cache.
     *
     * @param  string $key
     * @param  mixed  $value
     * @access public
     * @return void
     */
    public function set(string $key, $value)
    {
        $this->redis->set($key, json_encode($value, JSON_UNESCAPED_UNICODE));
    }

    /**
     * 根据表名和键获取对象。
     * Get object by table name and key.
     *
     * @param  string $table
     * @param  int|string $key
     * @access public
     * @return object
     */
    public function fetch(string $table, int|string $key)
    {
        if(empty($this->redis))                             return $this->log('Redis is not initialized.');
        if(empty($table))                                   return $this->log('Table name is required.');
        if(empty($key))                                     return $this->log('Key is required.');
        if(empty($this->config->redis->cache->raw[$table])) return $this->log('No cache settings for table ' . $table);

        $code = str_replace(['`', $this->config->db->prefix], '', $table);
        return $this->get("raw:{$code}:{$key}");
    }

    /**
     * 根据表名获取对象列表。
     * Get object list by table name.
     *
     * @param  string $table
     * @param  array  $keyList
     * @access public
     * @return array
     */
    public function fetchAll(string $table, array $keyList = [])
    {
        if(empty($this->redis))                             return $this->log('Redis is not initialized.');
        if(empty($table))                                   return $this->log('Table name is required.');
        if(empty($this->config->redis->cache->raw[$table])) return $this->log('No cache settings for table ' . $table);

        $keys = [];
        $code = str_replace(['`', $this->config->db->prefix], '', $table);
        if(!$keyList) $keyList = $this->redis->smembers("set:{$code}List");
        if(!$keyList) return [];

        foreach($keyList as $key) $keys[] = "raw:{$code}:{$key}";

        $objects  = $this->redis->mget($keys);
        $cacheKey = $this->config->redis->cache->raw[$table];

        $result = [];
        foreach($objects as $object)
        {
            $object = json_decode($object);
            if($object) $result[$object->$cacheKey] = $object;
        }

        return $result;
    }

    /**
     * 根据表名和键获取键值对。
     * Get key-value pairs by table name and key.
     *
     * @param  string $table
     * @param  string $key
     * @param  string $value
     * @param  array  $keyList
     * @access public
     * @return array
     */
    public function fetchPairs(string $table, string $key, string $value, array $keyList = [])
    {
        if(empty($this->redis))                             return $this->log('Redis is not initialized.');
        if(empty($table))                                   return $this->log('Table name is required.');
        if(empty($key))                                     return $this->log('Key is required.');
        if(empty($value))                                   return $this->log('Value is required.');
        if(empty($this->config->redis->cache->raw[$table])) return $this->log('No cache settings for table ' . $table);

        $objects = $this->fetchAll($table, $keyList);
        if(!$objects) return [];

        $paris = [];
        foreach($objects as $object) $pairs[$object->$key] = $object->$value;
        return $pairs;
    }

    /**
     * 创建缓存。
     * Create cache.
     *
     * @access private
     * @return void
     */
    private function create()
    {
        $objectID = $this->dao->lastInsertID();
        if(!$objectID) return $this->log('Failed to fetch last insert id.');

        $this->condition = "`id` = '{$objectID}'";

        $this->update();
    }

    /**
     * 更新缓存。
     * Update cache.
     *
     * @access private
     * @return void
     */
    private function update()
    {
        $objects = $this->dao->select('*')->from($this->table)
            ->beginIF($this->condition)->where($this->condition)->fi()
            ->fetchAll();
        if(!$objects) return $this->log("Failed to fetch objects to update table {$this->table}.", $this->dao->get());

        $code = str_replace(['`', $this->config->db->prefix], '', $this->table);
        $key  = $this->config->redis->cache->raw[$this->table];

        $pairs = [];
        foreach($objects as $object) $pairs["raw:{$code}:{$object->$key}"] = json_encode($object, JSON_UNESCAPED_UNICODE);
        $this->redis->mset($pairs);

        $members = [];
        foreach($objects as $object) $members[] = $object->$key;
        $this->redis->sadd("set:{$code}List", ...$members);
    }

    /**
     * 删除缓存。
     * Delete cache.
     *
     * @access private
     * @return void
     */
    private function delete()
    {
        if(empty($this->keyList)) return $this->log('Failed to fetch key list to delete.');

        $keyList = array_values($this->keyList);

        $keys = [];
        $code = str_replace(['`', $this->config->db->prefix], '', $this->table);
        foreach($keyList as $key) $keys[] = "raw:{$code}:{$key}";

        $this->redis->del($keys);
        $this->redis->srem("set:{$code}List", ...$keyList);
    }

    /**
     * 重置参数。
     * Reset parameters.
     *
     * @access public
     * @return void
     */
    public function reset(): void
    {
        $this->table     = '';
        $this->event     = '';
        $this->condition = '';
        $this->keyList   = [];
    }

    /**
     * 记录日志。
     * Log.
     *
     * @param  string $log
     * @param  string $sql
     * @access private
     * @return void
     */
    private function log(string $log, string $sql = ''): void
    {
        $runMode = PHP_SAPI == 'cli' ? '_cli' : '';
        $logFile = $this->app->getLogRoot() . 'redis' . $runMode . '.' . date('Ymd') . '.log.php';
        if(!file_exists($logFile)) file_put_contents($logFile, '<?php die(); ?' . ">\n");

        $content = date('Ymd H:i:s') . ': ' . $this->getURI() . "\n{$log}\n";

        if($sql) $content .= "The sql is: {$sql}\n";

        file_put_contents($logFile, $content, FILE_APPEND);
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
     * 调用 Redis 对象的方法。
     * Call the method of Redis object.
     *
     * @param  string $method
     * @param  array  $args
     * @access public
     * @return void
     */
    public function __call(string $method, array $args)
    {
        if(empty($this->redis)) helper::end('Redis is not initialized.');

        return $this->redis->$method(...$args);
    }
}
