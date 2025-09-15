#!/usr/bin/env php
<?php

/**

title=测试 cneModel::stopApp();
timeout=0
cid=0

- 正常应用停止请求 @object
- 空channel使用默认值 @object
- 无效参数情况 @object
- 自定义channel参数 @object
- 缺少参数情况 @object
- 服务器错误情况 @object

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

global $tester, $config;
$config->CNE->api->host   = 'http://devops.corp.cc:32380';
$config->CNE->api->token  = 'R09p3H5mU1JCg60NGPX94RVbGq31JVkF';
$config->CNE->app->domain = 'devops.corp.cc';

$cneTest = new cneTest();

r($cneTest->stopAppTest()) && p() && e('object'); // 正常应用停止请求
r($cneTest->stopAppWithEmptyChannelTest()) && p() && e('object'); // 空channel使用默认值
r($cneTest->stopAppWithInvalidParamsTest()) && p() && e('object'); // 无效参数情况
r($cneTest->stopAppWithCustomChannelTest()) && p() && e('object'); // 自定义channel参数
r($cneTest->stopAppWithMissingParamsTest()) && p() && e('object'); // 缺少参数情况
r($cneTest->stopAppWithServerErrorTest()) && p() && e('object'); // 服务器错误情况