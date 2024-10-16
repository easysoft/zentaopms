<?php
class zredis
{
    /**
     * 全局 $app 对象。
     * The global $app object.
     *
     * @var object
     * @access public
     */
    public $app;

    /**
     * 全局 $config 对象。
     * The global $config object.
     *
     * @var object
     * @access public
     */
    public $config;

    /**
     * $dao 对象，用于访问或者更新数据库。
     * The $dao object, used to access or update database.
     *
     * @var dao
     * @access public
     */
    public $dao;

    /**
     * $redis 对象，用于访问或者更新 Redis。
     * The $redis object, used to access or update database.
     *
     * @var redis
     * @access public
     */
    public $redis = null;

    public function __construct($dao)
    {
        global $app, $config;

        $this->app    = $app;
        $this->config = $config;
        $this->dao    = $dao;

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
            $this->app->triggerError($e->getMessage(), __FILE__, __LINE__, true);
        }
    }

    public function init()
    {
        if(empty($this->redis)) helper::end('Redis is not initialized.');

        $this->redis->multi();

        foreach($this->config->redis->tables as $table => $setting)
        {
            foreach($setting->caches as $cache)
            {
                $cache     = (object)$cache;
                $condition = isset($cache->condition) ? $cache->condition : '';
                $objects   = $this->dao->select('*')->from($table)->beginIF($condition)->where($condition)->fi()->fetchAll();
                if($cache->type == 'raw')
                {
                    $pairs = [];
                    foreach($objects as $object) $pairs["raw:{$cache->name}:{$object->{$setting->key}}"] = json_encode($object);
                    $this->redis->mset($pairs);
                }
                if($cache->type == 'set')
                {
                    $members = [];
                    foreach($objects as $object) $members[] = $object->{$setting->key};
                    $this->redis->sadd("set:{$cache->name}", ...$members);
                }
            }
        }

        $this->redis->set('inited', date('Y-m-d H:i:s'));

        $this->redis->exec();
    }

    public function prepareSync($table, $event, $sql)
    {
        $this->event = $event;
        $this->table = $table;
        $this->sql   = $sql;

        if($event = 'delete')
        {
            $condition = '';
            $idList    = [];
            if($method == 'update' || $method == 'delete')
            {
                $whereLen  = strlen(DAO::WHERE);
                $wherePOS  = strrpos($sql, DAO::WHERE);             // The position of WHERE keyword.
                $groupPOS  = strrpos($sql, DAO::GROUPBY);           // The position of GROUP BY keyword.
                $havingPOS = strrpos($sql, DAO::HAVING);            // The position of HAVING keyword.
                $orderPOS  = strrpos($sql, DAO::ORDERBY);           // The position of ORDERBY keyword.
                $limitPOS  = strrpos($sql, DAO::LIMIT);             // The position of LIMIT keyword.
                $splitPOS  = $orderPOS  ? $orderPOS  : $limitPOS;   // If $orderPOS, use it instead of $limitPOS.
                $splitPOS  = $havingPOS ? $havingPOS : $splitPOS;   // If $havingPOS, use it instead of $orderPOS.
                $splitPOS  = $groupPOS  ? $groupPOS  : $splitPOS;   // If $groupPOS, use it instead of $havingPOS.
                $condition = substr($sql, $wherePOS + $whereLen, $splitPOS - $wherePOS - $whereLen);

                if($method == 'delete') $idList = $this->dao->select('id')->from($table)->where($condition)->fetchPairs();
            }

            $this->conditions = $condition;
            $this->idList     = $idList;
        }
    }

    public function sync()
    {
    }

    public function update($table)
    {
    }

    public function delete($table, $idList)
    {
        if(empty($this->redis)) helper::end('Redis is not initialized.');
        if(empty($table)) helper::end('Table name is required.');
        if(empty($idList)) helper::end('ID list is required.');
        if(empty($this->config->redis->tables[$table]->caches)) helper::end('No cache settings for table ' . $table);

        foreach($this->config->redis->tables[$table]->caches as $cache)
        {
            if($cache->type == 'raw')
            {
                $keys = [];
                foreach($idList as $id) $keys[] = "raw:{$cache->name}:{$id}";
                $this->redis->del($keys);
            }
            if($cache->type == 'set')
            {
                $this->redis->srem("set:{$cache->name}", ...$idList);
            }
        }
    }

    public function reset()
    {
        $this->event     = '';
        $this->tabel     = '';
        $this->sql       = '';
        $this->condition = '';
        $this->idList    = '';
    }

    public function __call($method, $args)
    {
        if(empty($this->redis)) helper::end('Redis is not initialized.');

        $this->redis->$method(...$args);
    }
}
