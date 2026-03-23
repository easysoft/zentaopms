#!/usr/bin/env php
<?php

/**
title=测试 aiModel::getAgentsByIDs();
timeout=0
cid=15044

- 步骤1：正常情况，获取多个智能体 @3
- 步骤2：正常情况，获取单个智能体 @1
- 步骤3：空数组参数 @0
- 步骤4：不存在的ID @0
- 步骤5：包含已删除的ID @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

$table = zenData('ai_agent');
$table->id->range('1-10');
$table->name->range('智能体1,智能体2,智能体3,智能体4,智能体5,智能体6,智能体7,智能体8,智能体9,智能体10');
$table->desc->range('测试描述内容{10}');
$table->model->range('0,1,2,3,4,5');
$table->module->range('story{3},task{2},bug{2},doc{2},testcase{1}');
$table->source->range(',story.title,story.spec,,task.name,task.desc,,bug.title,bug.steps,');
$table->targetForm->range('story.change,task.edit,bug.edit,doc.edit,story.testcasecreate');
$table->purpose->range('测试目的内容{10}');
$table->elaboration->range('测试详细说明内容{10}');
$table->role->range('测试角色描述{10}');
$table->characterization->range('测试角色特征{10}');
$table->createdBy->range('admin,system,user1,user2');
$table->createdDate->range('`2023-08-10 10:00:00`,`2023-08-11 11:00:00`,`2023-08-12 12:00:00`');
$table->status->range('active{8},draft{2}');
$table->deleted->range('0{8},1{2}');
$table->gen(10);

su('admin');

$aiTest = new aiTest();

r(count($aiTest->getAgentsByIDsTest(array(1, 2, 3)))) && p() && e('3'); // 步骤1：正常情况，获取多个智能体
r(count($aiTest->getAgentsByIDsTest(array(1)))) && p() && e('1'); // 步骤2：正常情况，获取单个智能体
r(count($aiTest->getAgentsByIDsTest(array()))) && p() && e('0'); // 步骤3：空数组参数
r(count($aiTest->getAgentsByIDsTest(array(999)))) && p() && e('0'); // 步骤4：不存在的ID
r(count($aiTest->getAgentsByIDsTest(array(1, 2, 9)))) && p() && e('2'); // 步骤5：包含已删除的ID（ID 9 已删除，只返回2个）
