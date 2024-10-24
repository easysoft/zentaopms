<?php
$config->redis = new stdClass();
$config->redis->enable   = true;
$config->redis->host     = '127.0.0.1';
$config->redis->port     = 6379;
$config->redis->timeout  = 10;
$config->redis->username = null;
$config->redis->password = null;

define('CACHE_CONFIG',  TABLE_CONFIG);
define('CACHE_BUILD',   TABLE_BUILD);
define('CACHE_MODULE',  TABLE_MODULE);
define('CACHE_PRODUCT', TABLE_PRODUCT);
define('CACHE_PROJECT', TABLE_PROJECT);
define('CACHE_RELEASE', TABLE_RELEASE);
define('CACHE_USER',    TABLE_USER);

// Format : $config->redis->caches[TABLE_NAME] = 'KEY_FIELD';
// The TABLE_NAME is the name of the table in the database.
// The KEY_FIELD is the field of the table which is used to generate the key of the cache. It must be unique in the table.

$config->redis->caches = [];
$config->redis->caches[CACHE_CONFIG]  = 'id';
$config->redis->caches[CACHE_BUILD]   = 'id';
$config->redis->caches[CACHE_MODULE]  = 'id';
$config->redis->caches[CACHE_PRODUCT] = 'id';
$config->redis->caches[CACHE_PROJECT] = 'id';
$config->redis->caches[CACHE_RELEASE] = 'id';
$config->redis->caches[CACHE_USER]    = 'account';
