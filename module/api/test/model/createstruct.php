#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('doclib')->config('doclib')->gen(10);
zdTable('apistruct')->gen(0);
zdTable('apistruct_spec')->gen(0);
/**

title=测试 apiModel->createStruct();
timeout=0
cid=1

- 测试正常添加数据结构。 @1
- 测试结构名称为空时添加数据结构。 @0
- 测试结构名称为空时添加数据结构返回的错误信息。第name条的0属性 @『结构名』不能为空。

*/

global $tester;
$tester->loadModel('api');

$formData = new stdclass();
$formData->lib  = 1;
$formData->name = '数据结构1';

r($tester->api->createStruct($formData)) && p() && e(1); // 测试正常添加数据结构。

$formData = new stdclass();
$formData->lib  = 1;
$formData->name = '';

r($tester->api->createStruct($formData)) && p() && e(0);        // 测试结构名称为空时添加数据结构。
r(dao::getError()) && p('name:0') && e('『结构名』不能为空。'); // 测试结构名称为空时添加数据结构返回的错误信息。
