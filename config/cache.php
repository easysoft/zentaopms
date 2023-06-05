<?php

/* 缓存设置。Cache settings. */
$config->cache = new stdclass();
$config->cache->enable   = false;         // 是否开启缓存。Enable cache or not.
$config->cache->lifetime = 5 * 60;        // 缓存生存时间。The lifetime of cache.
$config->cache->driver   = 'File';        // 缓存驱动。   The driver of cache. can be File|Yac|Apcu.

$config->cache->enableFullPage   = false;   // 是否开启整页缓存。Enable full page cache or not.
$config->cache->fullPageLifetime = 5 * 60;
$config->cache->fullPageDriver   = 'File';

$config->cache->fullPages = array();
$config->cache->fullPages[] = 'program|browse';
$config->cache->fullPages[] = 'product|all';
$config->cache->fullPages[] = 'project|browse';
