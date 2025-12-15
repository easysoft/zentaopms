#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('doclib')->gen(5);
zenData('apistruct')->gen(0);
zenData('apistruct_spec')->gen(0);
zenData('action')->gen(0);

/**

title=测试 apiModel::createStruct();
timeout=0
cid=15099

- 测试正常创建数据结构 @1
- 测试结构名称为空时创建数据结构 @0
- 测试结构名称为空的错误提示信息第name条的0属性 @『结构名』不能为空。
- 测试attribute字段为空时创建数据结构 @1
- 测试attribute字段为空的错误提示信息第attribute条的0属性 @0
- 测试创建复杂数据结构 @1

*/

global $tester;
$tester->loadModel('api');

// 测试步骤1：正常创建数据结构
$validFormData = new stdclass();
$validFormData->lib       = 1;
$validFormData->name      = '用户数据结构';
$validFormData->attribute = '{"name":"string","age":"int"}';

r($tester->api->createStruct($validFormData)) && p() && e(1); // 测试正常创建数据结构

// 测试步骤2：结构名称为空时创建数据结构
$emptyNameData = new stdclass();
$emptyNameData->lib       = 1;
$emptyNameData->name      = '';
$emptyNameData->attribute = '{"id":"int"}';

r($tester->api->createStruct($emptyNameData)) && p() && e(0); // 测试结构名称为空时创建数据结构
r(dao::getError()) && p('name:0') && e('『结构名』不能为空。'); // 测试结构名称为空的错误提示信息

// 测试步骤3：attribute字段为空时创建数据结构
$emptyParamsData = new stdclass();
$emptyParamsData->lib       = 1;
$emptyParamsData->name      = '测试结构';
$emptyParamsData->attribute = '';

r($tester->api->createStruct($emptyParamsData)) && p() && e(1); // 测试attribute字段为空时创建数据结构
r(dao::getError()) && p('attribute:0') && e(0); // 测试attribute字段为空的错误提示信息

// 测试步骤4：创建复杂数据结构
$complexFormData = new stdclass();
$complexFormData->lib       = 1;
$complexFormData->name      = '复杂数据结构';
$complexFormData->type      = 'object';
$complexFormData->desc      = '包含多种字段类型的复杂结构';
$complexFormData->attribute = '{"items":[{"name":"string","status":"enum"}]}';

r($tester->api->createStruct($complexFormData)) && p() && e(1); // 测试创建复杂数据结构