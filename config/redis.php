<?php
$config->redis = new stdClass();
$config->redis->enable   = true;
$config->redis->host     = '127.0.0.1';
$config->redis->port     = 6379;
$config->redis->timeout  = 10;
$config->redis->username = null;
$config->redis->password = null;

// Format : $config->redis->cache->raw[TABLE_NAME] = 'KEY_FIELD';
// The TABLE_NAME is the name of the table in the database.
// The KEY_FIELD is the field of the table which is used to generate the key of the cache. It must be unique in the table.

$config->redis->cache = new stdClass();
$config->redis->cache->raw = [];
$config->redis->cache->raw[TABLE_CONFIG]  = 'id';
$config->redis->cache->raw[TABLE_BUILD]   = 'id';
$config->redis->cache->raw[TABLE_MODULE]  = 'id';
$config->redis->cache->raw[TABLE_PRODUCT] = 'id';
$config->redis->cache->raw[TABLE_PROJECT] = 'id';
$config->redis->cache->raw[TABLE_RELEASE] = 'id';
$config->redis->cache->raw[TABLE_USER]    = 'account';

$config->redis->cache->res = [];
$config->redis->cache->res[TABLE_MODULE][] = ['name' => 'CACHE_MODULE_TREE', 'fields' => ['type', 'root', 'branch']];

$config->cache->keys = [];
foreach($config->redis->cache->res as $table => $caches)
{
    foreach($caches as $cache)
    {
        $cache = (object)$cache;
        $cache->table = $table;
        $config->cache->keys[$cache->name] = $cache;
        define($cache->name, $cache->name);
    }
}
