<?php
$config->redis = new stdClass();
$config->redis->host     = '127.0.0.1';
$config->redis->port     = 6379;
$config->redis->timeout  = 10;
$config->redis->username = null;
$config->redis->password = null;

$config->redis->tables = [];
$config->redis->tables[TABLE_PROJECT] = new stdClass();
$config->redis->tables[TABLE_PROJECT]->key = 'id';
$config->redis->tables[TABLE_PROJECT]->caches[] = ['type' => 'raw', 'name' => 'execution'];
$config->redis->tables[TABLE_PROJECT]->caches[] = ['type' => 'set', 'name' => 'programIdList',   'condition' => "`type` = 'program'"];
$config->redis->tables[TABLE_PROJECT]->caches[] = ['type' => 'set', 'name' => 'projectIdList',   'condition' => "`type` = 'project'"];
$config->redis->tables[TABLE_PROJECT]->caches[] = ['type' => 'set', 'name' => 'executionIdList', 'condition' => "`type` IN ('sprint', 'stage', 'kanban')"];

$config->redis->tables[TABLE_PRODUCT] = new stdClass();
$config->redis->tables[TABLE_PRODUCT]->key = 'id';
$config->redis->tables[TABLE_PRODUCT]->caches[] = ['type' => 'raw', 'name' => 'product'];
$config->redis->tables[TABLE_PRODUCT]->caches[] = ['type' => 'set', 'name' => 'productIdList'];

$config->redis->tables[TABLE_USER] = new stdClass();
$config->redis->tables[TABLE_USER]->key = 'account';
$config->redis->tables[TABLE_USER]->caches[] = ['type' => 'raw', 'name' => 'user'];
$config->redis->tables[TABLE_USER]->caches[] = ['type' => 'set', 'name' => 'userAccountList'];
