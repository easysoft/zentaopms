#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 adminModel::getApiConfig();
timeout=0
cid=14979

- 执行admin模块的getApiConfigTest方法  @null
- 执行admin模块的getApiConfigWithCacheTest方法  @cached_config
- 执行admin模块的getApiConfigExpiredTest方法  @expired_refresh_failed
- 执行admin模块的getApiConfigNoResponseTest方法  @no_response
- 执行admin模块的getApiConfigInvalidFormatTest方法  @invalid_format

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/admin.unittest.class.php';

su('admin');

$admin = new adminTest();

r($admin->getApiConfigTest()) && p() && e('null');
r($admin->getApiConfigWithCacheTest()) && p() && e('cached_config');
r($admin->getApiConfigExpiredTest()) && p() && e('expired_refresh_failed');
r($admin->getApiConfigNoResponseTest()) && p() && e('no_response');
r($admin->getApiConfigInvalidFormatTest()) && p() && e('invalid_format');