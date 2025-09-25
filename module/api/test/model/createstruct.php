#!/usr/bin/env php
<?php

/**

title=测试 apiModel::createStruct();
timeout=0
cid=0

- 测试步骤1：测试正常添加数据结构 @1
- 测试步骤2：测试结构名称为空时添加数据结构 @0
- 测试步骤3：测试params字段为空时添加数据结构 @0
- 测试步骤4：测试边界值和特殊字符处理 @1
- 测试步骤5：测试复杂数据结构创建 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

// 数据准备
$doclib = zenData('doclib');
$doclib->id->range('1-5');
$doclib->name->range('API文档库1,API文档库2,API文档库3,API文档库4,API文档库5');
$doclib->type->range('api{5}');
$doclib->product->range('0{5}');
$doclib->project->range('0{5}');
$doclib->gen(5);

zenData('apistruct')->gen(0);
zenData('apistruct_spec')->gen(0);
zenData('action')->gen(0);

global $tester;
$tester->loadModel('api');

// 测试步骤1：测试正常添加数据结构
$validFormData = new stdclass();
$validFormData->lib    = 1;
$validFormData->name   = '用户数据结构';
$validFormData->params = '{"name":"string","age":"int"}';
$result1 = $tester->api->createStruct($validFormData);

// 测试步骤2：测试结构名称为空时添加数据结构
$emptyNameData = new stdclass();
$emptyNameData->lib    = 1;
$emptyNameData->name   = '';
$emptyNameData->params = '{"id":"int"}';
$result2 = $tester->api->createStruct($emptyNameData);

// 测试步骤3：测试params字段为空时添加数据结构
$emptyParamsData = new stdclass();
$emptyParamsData->lib  = 1;
$emptyParamsData->name = '测试结构';
$emptyParamsData->params = '';
$result3 = $tester->api->createStruct($emptyParamsData);

// 测试步骤4：测试边界值和特殊字符处理
$specialCharData = new stdclass();
$specialCharData->lib    = 1;
$specialCharData->name   = 'API结构_特殊字符@#$';
$specialCharData->params = '{"field":"string"}';
$result4 = $tester->api->createStruct($specialCharData);

// 测试步骤5：测试复杂数据结构创建
$complexFormData = new stdclass();
$complexFormData->lib    = 1;
$complexFormData->name   = '复杂数据结构';
$complexFormData->type   = 'object';
$complexFormData->desc   = '包含多种字段类型的复杂结构';
$complexFormData->params = '{"items":[{"name":"string","status":"enum"}]}';
$result5 = $tester->api->createStruct($complexFormData);

// 断言验证
r($result1) && p() && e(1); // 测试步骤1：测试正常添加数据结构
r($result2) && p() && e(0); // 测试步骤2：测试结构名称为空时添加数据结构
r($result3) && p() && e(0); // 测试步骤3：测试params字段为空时添加数据结构
r($result4) && p() && e(1); // 测试步骤4：测试边界值和特殊字符处理
r($result5) && p() && e(1); // 测试步骤5：测试复杂数据结构创建