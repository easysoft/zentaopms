#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 adminModel::getApiConfig();
timeout=0
cid=14979

- 首次请求读取模拟配置 @1,mock_session_123,mocksid,3600
- 使用 session 缓存配置 @1,test_session_123,zentaosid,3600
- 过期配置刷新后重新获取 @1,fresh_session_456,freshsid,7200
- 模拟接口无响应时返回空配置 @0
- 模拟返回格式非法时返回空配置 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$admin = new adminModelTest();

r($admin->getApiConfigTest()) && p('hasConfig,sessionID,sessionVar,expiredTime') && e('1,mock_session_123,mocksid,3600');
r($admin->getApiConfigWithCacheTest()) && p('hasConfig,sessionID,sessionVar,expiredTime') && e('1,test_session_123,zentaosid,3600');
r($admin->getApiConfigExpiredTest()) && p('hasConfig,sessionID,sessionVar,expiredTime') && e('1,fresh_session_456,freshsid,7200');
r($admin->getApiConfigNoResponseTest()) && p('hasConfig') && e('0');
r($admin->getApiConfigInvalidFormatTest()) && p('hasConfig') && e('0');
