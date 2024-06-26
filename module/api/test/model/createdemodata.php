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
cid=1

- 测试正常初始化数据。 @1
- 测试错误初始化数据。 @0

*/

global $tester, $lang, $app;
$tester->loadModel('api');

r($tester->api->createDemoData($lang->api->zentaoAPI, commonModel::getSysURL() . $app->config->webRoot . 'api.php/v1', '16.0')) && p() && e(1); // 测试正常初始化数据。
r($tester->api->createDemoData($lang->api->zentaoAPI, commonModel::getSysURL() . $app->config->webRoot . 'api.php/v2', '16.0')) && p() && e(0); // 测试错误初始化数据。
