#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printScrumRiskBlock();
timeout=0
cid=0

- 步骤1：正常参数测试 @1
- 步骤2：无效type参数测试 @1
- 步骤3：边界值count参数测试 @1
- 步骤4：空orderBy参数测试 @1
- 步骤5：null参数测试 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('risk');
$table->id->range('1-10');
$table->project->range('1{5},2{3},3{2}');
$table->execution->range('1-5');
$table->name->range('风险1,风险2,风险3,风险4,风险5,风险6,风险7,风险8,风险9,风险10');
$table->source->range('requirement,design,code,test');
$table->category->range('technical,management,external');
$table->strategy->range('avoid,mitigate,accept,transfer');
$table->status->range('active{7},closed{3}');
$table->impact->range('1,2,3,4,5');
$table->probability->range('1,2,3,4,5');
$table->rate->range('low,medium,high');
$table->pri->range('1,2,3,4');
$table->identifiedDate->range('`2024-01-01`:`2024-12-31`:1D');
$table->gen(10);

$userTable = zenData('user');
$userTable->id->range('1-5');
$userTable->account->range('admin,user1,user2,user3,user4');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4');
$userTable->gen(5);

$projectTable = zenData('project');
$projectTable->id->range('1-3');
$projectTable->name->range('项目1,项目2,项目3');
$projectTable->type->range('project{3}');
$projectTable->status->range('doing{2},closed{1}');
$projectTable->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 强制要求：必须包含至少5个测试步骤

// 创建测试用的block对象
$normalBlock = new stdClass();
$normalBlock->params = new stdClass();
$normalBlock->params->type = 'all';
$normalBlock->params->count = 15;
$normalBlock->params->orderBy = 'id_desc';

$invalidBlock = new stdClass();
$invalidBlock->params = new stdClass();
$invalidBlock->params->type = 'invalid_type';
$invalidBlock->params->count = 5;
$invalidBlock->params->orderBy = 'id_asc';

$boundaryBlock = new stdClass();
$boundaryBlock->params = new stdClass();
$boundaryBlock->params->type = 'all';
$boundaryBlock->params->count = 0;
$boundaryBlock->params->orderBy = 'id_desc';

$emptyOrderBlock = new stdClass();
$emptyOrderBlock->params = new stdClass();
$emptyOrderBlock->params->type = 'all';
$emptyOrderBlock->params->count = 10;
$emptyOrderBlock->params->orderBy = '';

$nullParamsBlock = new stdClass();

r($blockTest->printScrumRiskBlockTest($normalBlock)) && p() && e('1'); // 步骤1：正常参数测试
r($blockTest->printScrumRiskBlockTest($invalidBlock)) && p() && e('1'); // 步骤2：无效type参数测试
r($blockTest->printScrumRiskBlockTest($boundaryBlock)) && p() && e('1'); // 步骤3：边界值count参数测试
r($blockTest->printScrumRiskBlockTest($emptyOrderBlock)) && p() && e('1'); // 步骤4：空orderBy参数测试
r($blockTest->printScrumRiskBlockTest($nullParamsBlock)) && p() && e('0'); // 步骤5：null参数测试