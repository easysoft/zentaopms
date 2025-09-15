#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printScrumRoadMapBlock();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性productID @1
 - 属性roadMapID @1
 - 属性sync @1
- 步骤2：非数字产品ID
 - 属性productID @1
 - 属性roadMapID @2
 - 属性sync @1
- 步骤3：POST请求
 - 属性productID @2
 - 属性roadMapID @3
 - 属性sync @0
- 步骤4：空产品ID
 - 属性productID @1
 - 属性roadMapID @0
 - 属性sync @1
- 步骤5：会话设置验证
 - 属性session_set_called @1
 - 属性productID @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-5');
$table->name->range('产品A,产品B,产品C,产品D,产品E');
$table->code->range('PA,PB,PC,PD,PE');
$table->status->range('normal{5}');
$table->type->range('normal{5}');
$table->deleted->range('0{5}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($blockTest->printScrumRoadMapBlockTest(1, 1)) && p('productID,roadMapID,sync') && e('1,1,1'); // 步骤1：正常情况
r($blockTest->printScrumRoadMapBlockTest('abc', 2)) && p('productID,roadMapID,sync') && e('1,2,1'); // 步骤2：非数字产品ID
r($blockTest->printScrumRoadMapBlockTest(2, 3, true)) && p('productID,roadMapID,sync') && e('2,3,0'); // 步骤3：POST请求
r($blockTest->printScrumRoadMapBlockTest(0, 0)) && p('productID,roadMapID,sync') && e('1,0,1'); // 步骤4：空产品ID
r($blockTest->printScrumRoadMapBlockTest(3, 5)) && p('session_set_called,productID') && e('1,3'); // 步骤5：会话设置验证