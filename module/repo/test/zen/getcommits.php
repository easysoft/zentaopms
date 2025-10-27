#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getCommits();
timeout=0
cid=0

- 步骤1：正常情况返回3条记录 @3
- 步骤2：空路径返回3条记录 @3
- 步骤3：无效repo对象返回false @0
- 步骤4：Git版本库返回3条记录 @3
- 步骤5：Subversion版本库返回3条记录 @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('repo');
$table->id->range('1-5');
$table->name->range('test-repo{5}');
$table->path->range('/var/repos/test{5}');
$table->SCM->range('Git{3},Subversion{2}');
$table->encoding->range('UTF-8{5}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($repoTest->getCommitsTest((object)array('id' => 1, 'SCM' => 'Git'), '/src', 'master', 'branch', null, 1))) && p() && e('3'); // 步骤1：正常情况返回3条记录
r(count($repoTest->getCommitsTest((object)array('id' => 2, 'SCM' => 'Git'), '', 'develop', 'branch', null, 2))) && p() && e('3'); // 步骤2：空路径返回3条记录
r($repoTest->getCommitsTest(null, '/src', 'master', 'branch', null, 1)) && p() && e('0'); // 步骤3：无效repo对象返回false
r(count($repoTest->getCommitsTest((object)array('id' => 3, 'SCM' => 'Git'), '/lib', 'abcdef1234567890', 'commit', null, 3))) && p() && e('3'); // 步骤4：Git版本库返回3条记录
r(count($repoTest->getCommitsTest((object)array('id' => 4, 'SCM' => 'Subversion'), '/trunk', '12345', 'commit', null, 4))) && p() && e('3'); // 步骤5：Subversion版本库返回3条记录