<?php
class zredis
{
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
     * @param  dao    $dao
     * @access public
     */
    public function __construct($app)
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

            if(!$this->redis->exists('inited')) $this->init();

            return $this;
        }
        catch(RedisException $e)
        {
            $this->triggerError($e->getMessage(), __FILE__, __LINE__, true);
        }
    }

    /**
     * 初始化缓存。
     * Initialize cache.
     *
     * @access public
     * @return void
     */
    public function init()
    {
        if(empty($this->redis)) helper::end('Redis is not initialized.');

        $this->redis->multi();

        foreach(array_keys($this->config->redis->tables) as $table)
        {
            $this->table     = $table;
            $this->condition = '';
            $this->update();
        }

        $this->redis->set('initialized', date('Y-m-d H:i:s'));

        $this->redis->exec();
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
    public function prepare($table, $event, $sql)
    {
        if(empty($this->redis))                                 return $this->log('Redis is not initialized.');
        if(empty($table))                                       return $this->log('Table name is required.');
        if(empty($event))                                       return $this->log('Event type is required.');
        if(empty($sql))                                         return $this->log('SQL is required.');
        if(empty($this->config->redis->tables[$table]->caches)) return $this->log('No cache settings for table ' . $table);

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
                $field   = $this->config->redis->tables[$table]->key;
                $keyList = $this->dao->select($field)->from($table)->beginIF($condition)->where($condition)->fi()->fetchPairs();
            }
        }

        $this->condition = $condition;
        $this->keyList   = $keyList;
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
        if(empty($this->redis))                                       return $this->log('Redis is not initialized.');
        if(empty($this->table))                                       return $this->log('Table name is required.');
        if(empty($this->event))                                       return $this->log('Event type is required.');
        if(empty($this->config->redis->tables[$this->table]->caches)) return $this->log('No cache settings for table ' . $this->table);

        $this->redis->multi();

        if($this->event == 'insert') $this->create();
        if($this->event == 'update') $this->update();
        if($this->event == 'delete') $this->delete();

        $this->redis->set('synchronized', date('Y-m-d H:i:s'));

        $this->redis->exec();

        $this->reset();
    }

    /**
     * 检查参数。
     * Check parameters.
     *
     * @access private
     * @return void
     */
    private function check()
    {
        if(empty($this->table))                                       helper::end('Table name is required.');
        if(empty($this->redis))                                       helper::end('Redis is not initialized.');
        if(empty($this->config->redis->tables[$this->table]->caches)) helper::end('No cache settings for table ' . $this->table);
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
        $this->check();

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
        $this->check();

        $setting = $this->config->redis->tables[$this->table];

        foreach($setting->caches as $cache)
        {
            $cache          = (object)$cache;
            $cacheCondition = isset($cache->condition) ? $cache->condition : '';

            $objects = $this->dao->select('*')->from($this->table)
                ->where('1=1')
                ->beginIF($this->condition)->andWhere($this->condition)->fi()
                ->beginIF($cacheCondition)->andWhere($cacheCondition)->fi()
                ->fetchAll();
            if(!$objects)
            {
                $this->log("Failed to fetch objects to update table {$this->table}.", $this->dao->get());
                continue;
            }

            if($cache->type == 'raw')
            {
                $pairs = [];
                foreach($objects as $object) $pairs["raw:{$cache->name}:{$object->{$setting->key}}"] = json_encode($object);
                $this->redis->mset($pairs);
                continue;
            }

            if($cache->type == 'set')
            {
                $members = [];
                foreach($objects as $object) $members[] = $object->{$setting->key};
                $this->redis->sadd("set:{$cache->name}", ...$members);
            }
        }
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
        $this->check();

        if(empty($this->keyList)) return $this->log('Failed to fetch id list to delete.');

        foreach($this->config->redis->tables[$this->table]->caches as $cache)
        {
            if($cache->type == 'raw')
            {
                $keys = [];
                foreach($keyList as $key) $keys[] = "raw:{$cache->name}:{$key}";
                $this->redis->del($keys);
            }
            if($cache->type == 'set')
            {
                $this->redis->srem("set:{$cache->name}", ...$keyList);
            }
        }
    }

    /**
     * 重置参数。
     * Reset parameters.
     *
     * @access public
     * @return void
     */
    public function reset()
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
    private function log($log, $sql = '')
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
    public function __call($method, $args)
    {
        if(empty($this->redis)) helper::end('Redis is not initialized.');

        $this->redis->$method(...$args);
    }
}
