#!/usr/bin/env php
<?php

/**

title=测试 adminModel::getApiConfig();
timeout=0
cid=0

- 步骤1：测试正常情况下获取API配置信息 @Fail
- 步骤2：测试session中已存在有效配置的情况 @Success
- 步骤3：测试session中配置过期的情况 @Success
- 步骤4：测试API根地址无响应的情况 @Fail
- 步骤5：测试返回配置格式异常的情况 @Fail

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/admin.unittest.class.php';

zenData('user')->gen(5);
su('admin');

$admin = new adminTest();

r($admin->getApiConfigTest()) && p() && e('Fail'); // 步骤1：测试正常情况下获取API配置信息
r($admin->getApiConfigWithCacheTest()) && p() && e('Success'); // 步骤2：测试session中已存在有效配置的情况
r($admin->getApiConfigExpiredTest()) && p() && e('Success'); // 步骤3：测试session中配置过期的情况
r($admin->getApiConfigNoResponseTest()) && p() && e('Fail'); // 步骤4：测试API根地址无响应的情况
r($admin->getApiConfigInvalidFormatTest()) && p() && e('Fail'); // 步骤5：测试返回配置格式异常的情况