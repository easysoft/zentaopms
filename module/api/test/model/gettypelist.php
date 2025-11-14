#!/usr/bin/env php
<?php

/**

title=测试 apiModel::getTypeList();
timeout=0
cid=15119

- 步骤1：正常libID获取类型列表，检查返回数组长度 @14
- 步骤2：无效libID获取类型列表，检查只返回基本类型 @11
- 步骤3：libID为0获取类型列表，检查只返回基本类型 @11
- 步骤4：验证基本类型string存在属性string @string
- 步骤5：验证有数据结构的库返回结果包含结构名属性1 @UserInfo

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/api.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$doclib = zenData('doclib');
$doclib->id->range('1-5');
$doclib->name->range('Test API Lib{1}, Demo Lib{2}, Sample Lib{3}, Empty Lib{4}, Invalid Lib{5}');
$doclib->type->range('api{5}');
$doclib->gen(5);

$apistruct = zenData('apistruct');
$apistruct->id->range('1-10');
$apistruct->lib->range('1{3},2{2},3{3},4{0},5{2}');
$apistruct->name->range('UserInfo, ProductData, OrderDetail, ResponseStatus, ErrorInfo, TaskInfo, ProjectInfo, FileInfo, ConfigData, SystemInfo');
$apistruct->deleted->range('0{8},1{2}');
$apistruct->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$apiTest = new apiTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($apiTest->getTypeListTest(1))) && p() && e('14'); // 步骤1：正常libID获取类型列表，检查返回数组长度
r(count($apiTest->getTypeListTest(999))) && p() && e('11'); // 步骤2：无效libID获取类型列表，检查只返回基本类型
r(count($apiTest->getTypeListTest(0))) && p() && e('11'); // 步骤3：libID为0获取类型列表，检查只返回基本类型
r($apiTest->getTypeListTest(1)) && p('string') && e('string'); // 步骤4：验证基本类型string存在
r($apiTest->getTypeListTest(1)) && p('1') && e('UserInfo'); // 步骤5：验证有数据结构的库返回结果包含结构名