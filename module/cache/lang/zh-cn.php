<?php
$lang->cache->setting      = '缓存设置';
$lang->cache->clear        = '清除缓存';
$lang->cache->clearSuccess = '清除成功';
$lang->cache->status       = '缓存状态';
$lang->cache->driver       = '缓存服务';
$lang->cache->namespace    = '命名空间';
$lang->cache->scope        = '服务范围';
$lang->cache->memory       = '内存使用';
$lang->cache->usedMemory   = '总计 %s，已使用 %s';

$lang->cache->statusList[1] = '开启';
$lang->cache->statusList[0] = '关闭';

$lang->cache->driverList['apcu']  = 'APCu';
$lang->cache->driverList['redis'] = 'Redis';

$lang->cache->scopeList['private'] = '本应用独享';
$lang->cache->scopeList['shared']  = '多应用共享';

$lang->cache->apcu = new stdClass();
$lang->cache->apcu->notice     = '使用 APCu 缓存需要先加载 APCu 扩展。';
$lang->cache->apcu->notLoaded  = '请加载 APCu 扩展后再开启数据缓存';
$lang->cache->apcu->notEnabled = '请启用 apc.enabled 选项后再开启数据缓存';

$lang->cache->redis = new stdClass();
$lang->cache->redis->host               = 'Redis 主机';
$lang->cache->redis->port               = 'Redis 端口';
$lang->cache->redis->username           = 'Redis 用户名';
$lang->cache->redis->password           = 'Redis 密码';
$lang->cache->redis->database           = 'Redis 数据库';
$lang->cache->redis->serializer         = 'Redis 序列化器';
$lang->cache->redis->notice             = '使用 Redis 缓存需要先加载 Redis 扩展。';
$lang->cache->redis->notLoaded          = '请加载 Redis 扩展后再开启数据缓存。';
$lang->cache->redis->igbinaryNotLoaded  = '请加载 igbinary 扩展后再开启数据缓存。';

$lang->cache->redis->serializerList['php']      = 'PHP 内置序列化器';
$lang->cache->redis->serializerList['igbinary'] = 'igbinary';

$lang->cache->redis->tips = new stdClass();
$lang->cache->redis->tips->host       = '填写域名或 IP 地址，无需填写协议和端口号。';
$lang->cache->redis->tips->database   = '填写 Redis 数据库的编号，默认为 0。';
$lang->cache->redis->tips->serializer = '数据需要序列化后缓存。更改序列化器会清空缓存数据。';

$lang->cache->tips = new stdClass();
$lang->cache->tips->namespace = '命名空间用来防止不同应用间缓存数据冲突。启用缓存后更改命名空间会清空缓存数据。';
$lang->cache->tips->scope     = '如果缓存服务只有本应用使用请选择『本应用独享』，否则选择『多应用共享』。';
