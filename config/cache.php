<?php
/* 缓存设置。Cache settings. */
$config->cache = new stdclass();
$config->cache->enable    = false;       // 是否开启缓存。Enable cache or not.
$config->cache->driver    = '';          // 缓存驱动。    The driver of cache. Can be File|Yac|Apcu|Redis.
$config->cache->scope     = '';          // 缓存服务范围。The scope of cache. Can be private|shared.
$config->cache->namespace = '';          // 缓存命名空间。The namespace of cache.
$config->cache->lifetime  = 2592000;     // 缓存生存时间。The lifetime of cache. The default value is 30 days.

$config->cache->client = new stdClass();
$config->cache->client->enable = false; // 是否开启客户端缓存。Enable client cache or not.

// Format : $config->cache->raw[TABLE_NAME] = 'KEY_FIELD';
// The TABLE_NAME is the name of the table in the database.
// The KEY_FIELD is the field of the table which is used to generate the key of the cache. It must be unique in the table.

$config->cache->raw = [];
$config->cache->raw[TABLE_CONFIG]  = 'id';
$config->cache->raw[TABLE_BUILD]   = 'id';
$config->cache->raw[TABLE_MODULE]  = 'id';
$config->cache->raw[TABLE_PRODUCT] = 'id';
$config->cache->raw[TABLE_PROJECT] = 'id';
$config->cache->raw[TABLE_RELEASE] = 'id';
$config->cache->raw[TABLE_USER]    = 'account';

$config->cache->res = [];
$config->cache->res[TABLE_MODULE][] = ['name' => 'CACHE_MODULE_TREE', 'fields' => ['type', 'root', 'branch']];

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

$config->cache->enableFullPage   = false; // 是否开启整页缓存。Enable full page cache or not.
$config->cache->fullPageLifetime = 5 * 60;
$config->cache->fullPageDriver   = 'File';

$config->cache->fullPages = array();      // 需要整页缓存的页面，格式为：模块名|方法名。模块和方法名都是小写。The pages need to be cached, format: module|method. Both module and method are lowercase.
$config->cache->fullPages[] = 'program|browse';
$config->cache->fullPages[] = 'product|all';
$config->cache->fullPages[] = 'project|browse';
$config->cache->fullPages[] = 'block|printblock';
$config->cache->fullPages[] = 'screen|view';

$config->cacheKeys = new stdclass();

$config->cacheKeys->block = new stdclass();
$config->cacheKeys->block->welcome          = 'welcome';
$config->cacheKeys->block->contribute       = 'contribute';
$config->cacheKeys->block->projectStatistic = 'projectStatistic%s%s%s';
$config->cacheKeys->block->recentProject    = 'recentProject';

$config->cacheKeys->execution = new stdclass();
$config->cacheKeys->execution->ajaxGetDropMenuProjects   = 'ajaxDropMenuProjects';
$config->cacheKeys->execution->ajaxGetDropMenuExecutions = 'ajaxDropMenuExecutions';

$config->cacheKeys->bug = new stdclass();
$config->cacheKeys->bug->browse = 'bugBrowse%s';

$config->redis = new stdClass();
$config->redis->host       = '';
$config->redis->port       = '';
$config->redis->username   = '';
$config->redis->password   = '';
$config->redis->serializer = 'serialize'; // serialize|igbinary
