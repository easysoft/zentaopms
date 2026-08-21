#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getCommitsByObject();
timeout=0
cid=18053

- 步骤1：获取任务关联提交信息第0条的id属性 @1
- 步骤2：获取bug关联提交信息第0条的revision属性 @c808480afe22d3a55d94e91c59a8f3170212ade0
- 步骤3：获取需求关联提交信息第0条的comment属性 @代码注释
- 步骤4：测试不存在对象ID @0
- 步骤5：测试无效对象类型 @0
- 步骤6：测试边界值ID为0 @0
- 步骤7：测试负数ID @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$history = zenData('ops_repohistory');
$history->id->range('1');
$history->repo->range('1');
$history->revision->range('c808480afe22d3a55d94e91c59a8f3170212ade0');
$history->commit->range('1');
$history->comment->range('代码注释');
$history->committer->range('admin');
$history->time->range('01')->prefix('2026-01-')->postfix(' 00:00:00');
$history->gen(1);

$relation = zenData('relation');
$relation->product->range('1{3}');
$relation->execution->range('1{3}');
$relation->AType->range('revision{3}');
$relation->AID->range('1{3}');
$relation->AVersion->range('1{3}');
$relation->relation->range('commit{3}');
$relation->BType->range('task,bug,story');
$relation->BID->range('8001,4001,10001');
$relation->BVersion->range('1{3}');
$relation->extra->range('1{3}');
$relation->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$repoTest = new repoModelTest();

// 5. 执行测试步骤（至少5个）
r($repoTest->getCommitsByObjectTest(8001, 'task'))   && p('0:id')       && e('1');                  // 步骤1：获取任务关联提交信息
r($repoTest->getCommitsByObjectTest(4001, 'bug'))    && p('0:revision') && e('c808480afe22d3a55d94e91c59a8f3170212ade0'); // 步骤2：获取bug关联提交信息
r($repoTest->getCommitsByObjectTest(10001, 'story')) && p('0:comment')  && e('代码注释');             // 步骤3：获取需求关联提交信息
r($repoTest->getCommitsByObjectTest(999, 'task'))  && p()             && e('0');                   // 步骤4：测试不存在对象ID
r($repoTest->getCommitsByObjectTest(1, 'invalid')) && p()             && e('0');                   // 步骤5：测试无效对象类型
r($repoTest->getCommitsByObjectTest(0, 'task'))    && p()             && e('0');                   // 步骤6：测试边界值ID为0
r($repoTest->getCommitsByObjectTest(-1, 'bug'))    && p()             && e('0');                   // 步骤7：测试负数ID
