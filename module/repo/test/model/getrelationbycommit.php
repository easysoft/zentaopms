#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getRelationByCommit();
timeout=0
cid=18074

- 步骤1：根据代码库ID和提交版本获取任务关联信息第0条的type属性 @task
- 步骤2：根据代码库ID和提交版本获取缺陷关联信息第0条的id属性 @4
- 步骤3：根据代码库ID和提交版本获取需求关联信息第0条的type属性 @story
- 步骤4：测试不存在的提交版本查询 @0
- 步骤5：测试无效的代码库ID查询 @0
- 步骤6：测试获取所有类型的关联信息 @Array
- 步骤7：测试空提交版本参数查询 @(

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$taskTable = zenData('task');
$taskTable->id->range('1-20');
$taskTable->name->range('任务1,任务2,任务3,任务4,任务5,任务6,任务7,任务8{12}');
$taskTable->status->range('wait{5},doing{10},done{5}');
$taskTable->gen(20);

$bugTable = zenData('bug');
$bugTable->id->range('1-20');
$bugTable->title->range('缺陷1,缺陷2,缺陷3,缺陷4{16}');
$bugTable->status->range('active{10},resolved{10}');
$bugTable->gen(20);

$storyTable = zenData('story');
$storyTable->id->range('1-20');
$storyTable->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10{10}');
$storyTable->status->range('active{10},closed{10}');
$storyTable->gen(20);

$relationTable = zenData('relation');
$relationTable->id->range('1-6');
$relationTable->product->range('1{6}');
$relationTable->execution->range('1{6}');
$relationTable->AType->range('revision{6}');
$relationTable->AID->range('1{6}');
$relationTable->AVersion->range('1{6}');
$relationTable->relation->range('commit{6}');
$relationTable->BType->range('task,bug,story,task,bug,story');
$relationTable->BID->range('8,4,10,9,5,11');
$relationTable->BVersion->range('1{6}');
$relationTable->extra->range('1{6}');
$relationTable->gen(6);

$repoTable = zenData('repo');
$repoTable->id->range('1-5');
$repoTable->name->range('测试代码库1,测试代码库2,测试代码库3{3}');
$repoTable->SCM->range('Git{5}');
$repoTable->gen(5);

zenData('repohistory')->loadYaml('repohistory')->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$repoTest = new repoModelTest();

// 5. 测试步骤（必须包含至少5个测试步骤）
r($repoTest->getRelationByCommitTest(1, 'c808480afe22d3a55d94e91c59a8f3170212ade0', 'task')) && p('0:type') && e('task'); // 步骤1：根据代码库ID和提交版本获取任务关联信息
r($repoTest->getRelationByCommitTest(1, 'c808480afe22d3a55d94e91c59a8f3170212ade0', 'bug')) && p('0:id') && e('4'); // 步骤2：根据代码库ID和提交版本获取缺陷关联信息
r($repoTest->getRelationByCommitTest(1, 'c808480afe22d3a55d94e91c59a8f3170212ade0', 'story')) && p('0:type') && e('story'); // 步骤3：根据代码库ID和提交版本获取需求关联信息
r($repoTest->getRelationByCommitTest(1, 'nonexistent-commit-hash', 'task')) && p() && e('0'); // 步骤4：测试不存在的提交版本查询
r($repoTest->getRelationByCommitTest(999, 'c808480afe22d3a55d94e91c59a8f3170212ade0', 'task')) && p() && e('0'); // 步骤5：测试无效的代码库ID查询
r($repoTest->getRelationByCommitTest(1, 'c808480afe22d3a55d94e91c59a8f3170212ade0', '')) && p() && e('Array'); // 步骤6：测试获取所有类型的关联信息
r($repoTest->getRelationByCommitTest(1, '', 'task')) && p() && e('('); // 步骤7：测试空提交版本参数查询