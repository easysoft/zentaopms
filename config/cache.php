<?php

/* 缓存设置。Cache settings. */
$config->cache = new stdclass();
$config->cache->enable   = false;         // 是否开启缓存。Enable cache or not.
$config->cache->lifetime = 5 * 60;        // 缓存生存时间。The lifetime of cache.
$config->cache->driver   = 'File';        // 缓存驱动。   The driver of cache. can be File|Yac|Apcu.

$config->cache->enableFullPage   = false; // 是否开启整页缓存。Enable full page cache or not.
$config->cache->fullPageLifetime = 5 * 60;
$config->cache->fullPageDriver   = 'File';

$config->cache->fullPages = array();      // 需要整页缓存的页面，格式为：模块名|方法名。模块和方法名都是小写。The pages need to be cached, format: module|method. Both module and method are lowercase.
$config->cache->fullPages[] = 'program|browse';
$config->cache->fullPages[] = 'product|all';
$config->cache->fullPages[] = 'project|browse';
$config->cache->fullPages[] = 'block|printblock';
$config->cache->fullPages[] = 'screen|view';
