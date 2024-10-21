<?php
$config->redis = new stdClass();
$config->redis->enable   = true;
$config->redis->host     = '127.0.0.1';
$config->redis->port     = 6379;
$config->redis->timeout  = 10;
$config->redis->username = null;
$config->redis->password = null;

// Format : $config->redis->caches[TABLE_NAME]  = 'KEY_FIELD';
// The TABLE_NAME is the name of the table in the database.
// The KEY_FIELD is the field of the table which is used to generate the key of the cache. It must be unique in the table.

$config->redis->caches = [];
$config->redis->caches[TABLE_CONFIG]  = 'id';
$config->redis->caches[TABLE_BUILD]   = 'id';
$config->redis->caches[TABLE_MODULE]  = 'id';
$config->redis->caches[TABLE_PRODUCT] = 'id';
$config->redis->caches[TABLE_PROJECT] = 'id';
$config->redis->caches[TABLE_RELEASE] = 'id';
$config->redis->caches[TABLE_USER]    = 'account';
