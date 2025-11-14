#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('doclib')->gen(0);
zenData('module')->gen(0);
zenData('api')->gen(0);
zenData('apistruct')->gen(0);
zenData('apistruct_spec')->gen(0);
zenData('apispec')->gen(0);
zenData('api_lib_release')->gen(0);

/**

title=测试 apiModel->createDemoData();
timeout=0
cid=15093

- 测试v1初始化数据。 @1
- 测试v2初始化数据。 @2
- 测试v3初始化数据。 @3
- 测试v3初始化数据。 @4
- 测试v3初始化数据。 @5

*/

global $tester, $lang, $app;
$tester->loadModel('api');

r($tester->api->createDemoData($lang->api->zentaoAPI . 'v1', commonModel::getSysURL() . $app->config->webRoot . 'api.php/v1', '16.0')) && p() && e(1); // 测试v1初始化数据。
r($tester->api->createDemoData($lang->api->zentaoAPI . 'v2', commonModel::getSysURL() . $app->config->webRoot . 'api.php/v2', '16.0')) && p() && e(2); // 测试v2初始化数据。
r($tester->api->createDemoData($lang->api->zentaoAPI . 'v3', commonModel::getSysURL() . $app->config->webRoot . 'api.php/v3', '16.0')) && p() && e(3); // 测试v3初始化数据。
r($tester->api->createDemoData($lang->api->zentaoAPI . 'v4', commonModel::getSysURL() . $app->config->webRoot . 'api.php/v4', '16.0')) && p() && e(4); // 测试v3初始化数据。
r($tester->api->createDemoData($lang->api->zentaoAPI . 'v5', commonModel::getSysURL() . $app->config->webRoot . 'api.php/v5', '16.0')) && p() && e(5); // 测试v3初始化数据。
