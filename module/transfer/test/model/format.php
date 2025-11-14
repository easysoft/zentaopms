#!/usr/bin/env php
<?php

/**

title=测试 transferModel::format();
timeout=0
cid=19313

- 步骤1：空模块情况 @Module is empty
- 步骤2：正常模块输入属性error @~~
- 步骤3：任务模块测试属性error @~~
- 步骤4：带过滤条件属性error @~~
- 步骤5：缺陷模块测试属性error @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/transfer.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('story');
$table->id->range('1-10');
$table->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10');
$table->status->range('active{5},closed{3},draft{2}');
$table->pri->range('1{3},2{4},3{3}');
$table->product->range('1{8},2{2}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$transferTest = new transferTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($transferTest->formatTest('', '')) && p() && e('Module is empty'); // 步骤1：空模块情况
r($transferTest->formatTest('story', '')) && p('error') && e('~~'); // 步骤2：正常模块输入
r($transferTest->formatTest('task', '')) && p('error') && e('~~'); // 步骤3：任务模块测试
r($transferTest->formatTest('story', 'active')) && p('error') && e('~~'); // 步骤4：带过滤条件
r($transferTest->formatTest('bug', '')) && p('error') && e('~~'); // 步骤5：缺陷模块测试