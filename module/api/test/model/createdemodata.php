#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('doclib')->gen(0);
zdTable('module')->gen(0);
zdTable('api')->gen(0);
zdTable('apistruct')->gen(0);
zdTable('apistruct_spec')->gen(0);
zdTable('apispec')->gen(0);
zdTable('api_lib_release')->gen(0);

/**

title=测试 apiModel->createDemoData();
timeout=0
cid=1

*/

global $tester, $lang, $app;
$tester->loadModel('api');

$app->setAppRoot('', dirname(__FILE__, 5));
r($tester->api->createDemoData($lang->api->zentaoAPI, commonModel::getSysURL() . $app->config->webRoot . 'api.php/v1', '16.0')) && p() && e(1); // 测试正常初始化数据。
