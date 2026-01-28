#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getJiraArchivedProject();
timeout=0
cid=15773

- 步骤1：正常情况 @0
- 步骤2：空列表 @0
- 步骤3：无效输入 @0
- 步骤4：混合数据 @0
- 步骤5：大量数据 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertModelTest();

// 4. 准备测试数据
$normalProjectList = array(
    (object)array('id' => 1, 'name' => 'Project 1', 'key' => 'PROJ1'),
    (object)array('id' => 2, 'name' => 'Project 2', 'key' => 'PROJ2'),
    (object)array('id' => 3, 'name' => 'Project 3', 'key' => 'PROJ3')
);

$emptyProjectList = array();

$invalidProjectList = array(
    'invalid_data',
    123,
    null
);

$mixedProjectList = array(
    (object)array('id' => 10, 'name' => 'Active Project', 'key' => 'ACTIVE'),
    (object)array('id' => 11, 'name' => 'Archived Project', 'key' => 'ARCHIVED'),
    (object)array('id' => 12, 'name' => 'Another Project', 'key' => 'ANOTHER')
);

$largeProjectList = array();
for($i = 1; $i <= 100; $i++) {
    $largeProjectList[] = (object)array(
        'id' => $i,
        'name' => 'Project ' . $i,
        'key' => 'PROJ' . $i
    );
}

// 5. 强制要求：必须包含至少5个测试步骤
r($convertTest->getJiraArchivedProjectTest($normalProjectList)) && p() && e('0'); // 步骤1：正常情况
r($convertTest->getJiraArchivedProjectTest($emptyProjectList)) && p() && e('0'); // 步骤2：空列表
r($convertTest->getJiraArchivedProjectTest($invalidProjectList)) && p() && e('0'); // 步骤3：无效输入
r($convertTest->getJiraArchivedProjectTest($mixedProjectList)) && p() && e('0'); // 步骤4：混合数据
r($convertTest->getJiraArchivedProjectTest($largeProjectList)) && p() && e('0'); // 步骤5：大量数据