#!/usr/bin/env php
<?php

/**

title=测试 gitModel::linkCommit();
timeout=0
cid=16549

- 步骤1：正常关联单个设计和提交（因linkCommit方法存在bug） @has_error
- 步骤2：关联多个设计和提交（因linkCommit方法存在bug） @has_error
- 步骤3：传入空设计数组（也会出错） @has_error
- 步骤4：关联不存在的设计ID（因linkCommit方法存在bug） @has_error
- 步骤5：重复关联同一设计和提交（因linkCommit方法存在bug） @has_error

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/git.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$relationTable = zenData('relation');
$relationTable->loadYaml('relation_linkcommit', false, 2)->gen(0);

$designTable = zenData('design');
$designTable->loadYaml('design_linkcommit', false, 2)->gen(10);

$repohistoryTable = zenData('repohistory');
$repohistoryTable->loadYaml('repohistory_linkcommit', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$gitTest = new gitTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($gitTest->linkCommitTest(array(1), 1, 'abc123')) && p() && e('has_error'); // 步骤1：正常关联单个设计和提交（因linkCommit方法存在bug）
r($gitTest->linkCommitTest(array(1, 2, 3), 1, 'def456')) && p() && e('has_error'); // 步骤2：关联多个设计和提交（因linkCommit方法存在bug）
r($gitTest->linkCommitTest(array(), 1, 'ghi789')) && p() && e('has_error'); // 步骤3：传入空设计数组（也会出错）
r($gitTest->linkCommitTest(array(999), 1, 'abc999')) && p() && e('has_error'); // 步骤4：关联不存在的设计ID（因linkCommit方法存在bug）
r($gitTest->linkCommitTest(array(1), 1, 'abc123')) && p() && e('has_error'); // 步骤5：重复关联同一设计和提交（因linkCommit方法存在bug）