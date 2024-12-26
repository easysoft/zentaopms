<?php
/* 缓存设置。Cache settings. */
$config->cache = new stdclass();
$config->cache->enable    = false;       // 是否开启缓存。Enable cache or not.
$config->cache->driver    = 'apcu';      // 缓存驱动。    The driver of cache. Can be file|yac|apcu|redis.
$config->cache->scope     = '';          // 缓存服务范围。The scope of cache. Can be private|shared.
$config->cache->namespace = '';          // 缓存命名空间。The namespace of cache.
$config->cache->lifetime  = 0;           // 缓存生存时间，默认永不过期。The lifetime of cache. Default is no expiration.

$config->cache->dao = new stdClass();
$config->cache->dao->enable   = true;    // 是否开启 DAO 缓存。Enable DAO cache or not.
$config->cache->dao->lifetime = 604800;  // DAO 缓存生存时间，默认为 7 天。The lifetime of DAO cache. Default is 7 days.

$config->cache->client = new stdClass();
$config->cache->client->enable = false; // 是否开启客户端缓存。Enable client cache or not.

// Format : $config->cache->raw[TABLE_NAME] = 'KEY_FIELD';
// The TABLE_NAME is the name of the table in the database.
// The KEY_FIELD is the field of the table which is used to generate the key of the cache. It must be unique in the table.

$config->cache->raw = [];
$config->cache->raw[TABLE_ACL]         = 'id';
$config->cache->raw[TABLE_CONFIG]      = 'id';
$config->cache->raw[TABLE_BUILD]       = 'id';
$config->cache->raw[TABLE_MODULE]      = 'id';
$config->cache->raw[TABLE_PRODUCT]     = 'id';
$config->cache->raw[TABLE_PROJECT]     = 'id';
$config->cache->raw[TABLE_RELEASE]     = 'id';
$config->cache->raw[TABLE_STAKEHOLDER] = 'id';
$config->cache->raw[TABLE_TEAM]        = 'id';
$config->cache->raw[TABLE_USER]        = 'account';
$config->cache->raw[TABLE_USERVIEW]    = 'account';

$config->cache->res = [];
$config->cache->res[TABLE_MODULE][]  = ['name' => 'CACHE_MODULE_TREE', 'fields' => ['type', 'root', 'branch']];
$config->cache->res[TABLE_PROJECT][] = ['name' => 'CACHE_PROJECT_TYPE'];

$config->cache->keys = [];
foreach($config->cache->res as $table => $caches)
{
    foreach($caches as $cache)
    {
        $cache = (object)$cache;
        $cache->table = $table;
        $config->cache->keys[$cache->name] = $cache;
        define($cache->name, $cache->name);
    }
}

$config->redis = new stdClass();
$config->redis->host       = '';
$config->redis->port       = '';
$config->redis->username   = '';
$config->redis->password   = '';
$config->redis->database   = 0;
$config->redis->serializer = 'igbinary'; // php|igbinary
