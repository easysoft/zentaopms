#!/usr/bin/env php
<?php

/**

title=测试 taskModel::getLinkedCommits();
timeout=0
cid=18810

- 步骤1：正常情况获取关联提交记录 @0
- 步骤2：空版本号数组 @0
- 步骤3：不存在的代码仓库ID @0
- 步骤4：不存在的版本号 @0
- 步骤5：无关联任务的版本号 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$repohistoryTable = zenData('repohistory');
$repohistoryTable->loadYaml('repohistory_getlinkedcommits', false, 2)->gen(10);

$relationTable = zenData('relation');
$relationTable->loadYaml('relation_getlinkedcommits', false, 2)->gen(10);

$taskTable = zenData('task');
$taskTable->loadYaml('task_getlinkedcommits', false, 2)->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($taskTest->getLinkedCommitsTest(1, array('abc123', 'def456'))) && p() && e('0'); // 步骤1：正常情况获取关联提交记录
r($taskTest->getLinkedCommitsTest(1, array())) && p() && e('0'); // 步骤2：空版本号数组
r($taskTest->getLinkedCommitsTest(999, array('abc123'))) && p() && e('0'); // 步骤3：不存在的代码仓库ID
r($taskTest->getLinkedCommitsTest(1, array('nonexistent'))) && p() && e('0'); // 步骤4：不存在的版本号
r($taskTest->getLinkedCommitsTest(1, array('xyz999'))) && p() && e('0'); // 步骤5：无关联任务的版本号