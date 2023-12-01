#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('doclib')->config('doclib')->gen(10);
zdTable('module')->config('module')->gen(10);
zdTable('api')->gen(10);
zdTable('apistruct')->gen(10);
zdTable('api_lib_release')->gen(0);
zdTable('apispec')->gen(0);

/**

title=测试 apiModel->publishLib();
timeout=0
cid=1

- 测试正常添加发布。 @1
- 测试版本号为空。 @0
- 测试版本号为空的提示信息。第version条的0属性 @『版本号』不能为空。

*/

global $tester;
$tester->loadModel('api');

$formData = new stdclass();
$formData->lib     = '1';
$formData->version = 'version1.0';
$formData->desc    = '我是描述';

r($tester->api->publishLib($formData)) && p() && e(1); // 测试正常添加发布。

$formData = new stdclass();
$formData->lib     = '1';
$formData->version = '';
$formData->desc    = '我是描述';

r($tester->api->publishLib($formData)) && p() && e(0);             // 测试版本号为空。
r(dao::getError()) && p('version:0') && e('『版本号』不能为空。'); // 测试版本号为空的提示信息。
