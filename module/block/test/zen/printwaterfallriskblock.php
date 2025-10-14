#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printWaterfallRiskBlock();
timeout=0
cid=0

- 步骤1：默认参数情况，验证type属性属性type @all
- 步骤2：默认参数情况，验证count属性属性count @15
- 步骤3：默认参数情况，验证orderBy属性属性orderBy @id_desc
- 步骤4：默认参数情况，验证hasValidation属性属性hasValidation @1
- 步骤5：指定参数情况
 - 属性type @active
 - 属性count @10
 - 属性orderBy @pri_desc

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('risk');
$table->id->range('1-10');
$table->project->range('1{5},2{3},3{2}');
$table->name->range('风险1,风险2,风险3,风险4,风险5,风险6,风险7,风险8,风险9,风险10');
$table->status->range('active{7},closed{3}');
$table->pri->range('high{3},medium{4},low{3}');
$table->assignedTo->range('user1,user2,user3,user4,user5,user1,user2,user3,user4,user5');
$table->gen(10);

$userTable = zenData('user');
$userTable->id->range('1-5');
$userTable->account->range('user1,user2,user3,user4,user5');
$userTable->realname->range('用户1,用户2,用户3,用户4,用户5');
$userTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($blockTest->printWaterfallRiskBlockTest()) && p('type') && e('all'); // 步骤1：默认参数情况，验证type属性
r($blockTest->printWaterfallRiskBlockTest()) && p('count') && e('15'); // 步骤2：默认参数情况，验证count属性
r($blockTest->printWaterfallRiskBlockTest()) && p('orderBy') && e('id_desc'); // 步骤3：默认参数情况，验证orderBy属性
r($blockTest->printWaterfallRiskBlockTest()) && p('hasValidation') && e('1'); // 步骤4：默认参数情况，验证hasValidation属性
r($blockTest->printWaterfallRiskBlockTest((object)array('params' => (object)array('type' => 'active', 'count' => '10', 'orderBy' => 'pri_desc')))) && p('type,count,orderBy') && e('active,10,pri_desc'); // 步骤5：指定参数情况