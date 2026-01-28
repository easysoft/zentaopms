#!/usr/bin/env php
<?php

/**

title=测试 repoModel::unlinkObjectBranch();
timeout=0
cid=18108

- 步骤1：删除存在的关系记录 @1
- 步骤2：删除不存在的记录 @1
- 步骤3：无效对象ID @1
- 步骤4：空分支名 @1
- 步骤5：无效代码库ID @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('relation');
$table->loadYaml('relation_unlinkobjectbranch', false, 2);
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$repoTest = new repoModelTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($repoTest->unlinkObjectBranchTest(1, 'story', 1, 'master')) && p() && e('1'); // 步骤1：删除存在的关系记录
r($repoTest->unlinkObjectBranchTest(999, 'task', 999, 'nonexist')) && p() && e('1'); // 步骤2：删除不存在的记录
r($repoTest->unlinkObjectBranchTest(0, 'bug', 1, 'develop')) && p() && e('1'); // 步骤3：无效对象ID
r($repoTest->unlinkObjectBranchTest(1, 'testcase', 2, '')) && p() && e('1'); // 步骤4：空分支名
r($repoTest->unlinkObjectBranchTest(2, 'story', 0, 'feature/test')) && p() && e('1'); // 步骤5：无效代码库ID