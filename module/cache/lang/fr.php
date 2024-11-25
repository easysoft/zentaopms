<?php
$lang->cache->setting      = 'Cache Setting';
$lang->cache->clear        = 'Clear Cache';
$lang->cache->clearSuccess = 'Cache cleared successfully.';
$lang->cache->status       = 'Status';
$lang->cache->driver       = 'Cache Type';
$lang->cache->namespace    = 'Namespace';
$lang->cache->scope        = 'Scope';
$lang->cache->memory       = 'Memory';
$lang->cache->usedMemory   = 'Total %s, used %s';

$lang->cache->statusList[1] = 'On';
$lang->cache->statusList[0] = 'Off';

$lang->cache->driverList['apcu']  = 'APCu';
$lang->cache->driverList['redis'] = 'Redis';

$lang->cache->scopeList['private'] = 'Exclusively for this application';
$lang->cache->scopeList['shared']  = 'Shared by multiple applications';

$lang->cache->apcu = new stdClass();
$lang->cache->apcu->notice     = 'To use APCu cache, you need to load the APCu extension first.';
$lang->cache->apcu->notLoaded  = 'Please load the APCu extension before enabling cache.';
$lang->cache->apcu->notEnabled = 'Please enable the apc.enabled option before enabling cach.';

$lang->cache->redis = new stdClass();
$lang->cache->redis->host               = 'Redis Host';
$lang->cache->redis->port               = 'Redis Port';
$lang->cache->redis->username           = 'Redis User';
$lang->cache->redis->password           = 'Redis Password';
$lang->cache->redis->serializer         = 'Redis Serializer';
$lang->cache->redis->notice             = 'To use Redis cache, you need to load the Redis extension first.';
$lang->cache->redis->notLoaded          = 'Please load the Redis extension before enabling cach.';
$lang->cache->redis->igbinaryNotLoaded  = 'Please load the igbinary extension before enabling cach.';

$lang->cache->redis->serializerList['php']      = 'PHP Serialize';
$lang->cache->redis->serializerList['igbinary'] = 'igbinary';

$lang->cache->redis->tips = new stdClass();
$lang->cache->redis->tips->host       = 'Fill in the domain name or IP address, and do not need to fill in the protocol and port number.';
$lang->cache->redis->tips->database   = 'Fill in the number of the Redis database, the default is 0.';
$lang->cache->redis->tips->serializer = 'Data needs to be serialized and cached. Changing the serializer will clear the cached data.';

$lang->cache->tips = new stdClass();
$lang->cache->tips->namespace = 'Namespaces are used to prevent cache data conflicts between different applications. Changing the namespace after caching is enabled will clear the cache data.';
$lang->cache->tips->scope     = 'If the cache service is only used by this application, please select "Exclusively for this application", otherwise select "Shared by multiple applications".';
