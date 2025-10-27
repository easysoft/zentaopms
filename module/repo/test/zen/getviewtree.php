#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getViewTree();
timeout=0
cid=0

- 步骤1：Gitlab版本库 @2
- 步骤2：Git版本库 @2
- 步骤3：SVN版本库 @2
- 步骤4：空entry参数 @2
- 步骤5：未知SCM类型 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. zendata数据准备
$table = zenData('repo');
$table->id->range('1-3');
$table->name->range('test-repo,git-repo,svn-repo');
$table->SCM->range('Gitlab,Git,Subversion');
$table->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$repoZenTest = new repoZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r(count($repoZenTest->getViewTreeTest((object)array('id' => 1, 'SCM' => 'Gitlab'), '', 'main'))) && p() && e('2'); // 步骤1：Gitlab版本库
r(count($repoZenTest->getViewTreeTest((object)array('id' => 2, 'SCM' => 'Git'), '', 'HEAD'))) && p() && e('2'); // 步骤2：Git版本库
r(count($repoZenTest->getViewTreeTest((object)array('id' => 3, 'SCM' => 'Subversion'), '/', '1'))) && p() && e('2'); // 步骤3：SVN版本库
r(count($repoZenTest->getViewTreeTest((object)array('id' => 2, 'SCM' => 'Git'), '', 'HEAD'))) && p() && e('2'); // 步骤4：空entry参数
r(count($repoZenTest->getViewTreeTest((object)array('id' => 999, 'SCM' => 'Unknown'), '', ''))) && p() && e('0'); // 步骤5：未知SCM类型