#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printAssignToMeBlock();
timeout=0
cid=0

- 步骤1：默认参数测试属性success @1
- 步骤2：空对象测试属性hasViewPriv @1
- 步骤3：自定义count参数属性hasData @1
- 步骤4：count为0测试属性totalCount @0
- 步骤5：完整参数测试属性totalCount @14

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('user');
$table->id->range('101-110');
$table->account->range('testuser101,testuser102,testuser103,testuser104,testuser105,testuser106,testuser107,testuser108,testuser109,testuser110');
$table->realname->range('测试用户{10}');
$table->role->range('user{10}');
$table->gen(10);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品{5}');
$product->deleted->range('0{5}');
$product->shadow->range('0{4},1{1}');
$product->gen(5);

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目{5}');
$project->deleted->range('0{5}');
$project->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($blockTest->printAssignToMeBlockTest()) && p('success') && e('1'); // 步骤1：默认参数测试
r($blockTest->printAssignToMeBlockTest(null)) && p('hasViewPriv') && e('1'); // 步骤2：空对象测试
r($blockTest->printAssignToMeBlockTest((object)array('params' => (object)array('count' => 20)))) && p('hasData') && e('1'); // 步骤3：自定义count参数
r($blockTest->printAssignToMeBlockTest((object)array('params' => (object)array('count' => 0)))) && p('totalCount') && e('0'); // 步骤4：count为0测试
r($blockTest->printAssignToMeBlockTest((object)array('params' => (object)array('count' => 10)))) && p('totalCount') && e('14'); // 步骤5：完整参数测试