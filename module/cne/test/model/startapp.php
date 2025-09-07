#!/usr/bin/env php
<?php

/**

title=测试 cneModel::startApp();
timeout=0
cid=1

- 使用完整有效参数启动应用 @object
- 使用空channel参数 @object
- 使用无效参数 @object
- 缺少必要参数 @object
- 使用null参数 @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

global $tester, $config;
$config->CNE->api->host   = 'http://devops.corp.cc:32380';
$config->CNE->api->token  = 'R09p3H5mU1JCg60NGPX94RVbGq31JVkF';
$config->CNE->app->domain = 'devops.corp.cc';

$cneTest = new cneTest();

r($cneTest->startAppTest()) && p() && e('object'); // 使用完整有效参数启动应用
r($cneTest->startAppWithEmptyChannelTest()) && p() && e('object'); // 使用空channel参数
r($cneTest->startAppWithInvalidParamsTest()) && p() && e('object'); // 使用无效参数
r($cneTest->startAppWithMissingParamsTest()) && p() && e('object'); // 缺少必要参数
r($cneTest->startAppWithNullParamsTest()) && p() && e('~~'); // 使用null参数