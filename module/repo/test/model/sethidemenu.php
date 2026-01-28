#!/usr/bin/env php
<?php

/**

title=测试 repoModel::setHideMenu();
timeout=0
cid=18103

- 步骤1：execution环境下有Gitlab代码库时返回对象ID @101
- 步骤2：execution环境下无代码库时返回对象ID @102
- 步骤3：project环境下有代码库时返回对象ID @103
- 步骤4：waterfall环境下有代码库时返回对象ID @104
- 步骤5：execution环境下有多个不同类型代码库时返回对象ID @105

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 数据准备 - 直接插入数据，避免zenData配置问题
global $tester;

// 清空相关表数据
$tester->dao->delete()->from(TABLE_REPO)->exec();
$tester->dao->delete()->from(TABLE_PROJECT)->exec();

// 插入测试用的代码库数据
$repos = array(
    array('id' => 1, 'product' => '1', 'name' => '[Gitlab]testGitlab', 'SCM' => 'Gitlab', 'serviceHost' => 1, 'deleted' => 0, 'projects' => '11'),
    array('id' => 2, 'product' => '2', 'name' => '[Gitea]testGitea', 'SCM' => 'Gitea', 'serviceHost' => 2, 'deleted' => 0, 'projects' => '12'),
    array('id' => 3, 'product' => '3', 'name' => '[Git]testGit', 'SCM' => 'Git', 'serviceHost' => 0, 'deleted' => 0, 'projects' => '13'),
    array('id' => 4, 'product' => '4', 'name' => '[Subversion]testSvn', 'SCM' => 'Subversion', 'serviceHost' => 0, 'deleted' => 0, 'projects' => '14'),
    array('id' => 5, 'product' => '5', 'name' => '[Git]testMixed', 'SCM' => 'Git', 'serviceHost' => 3, 'deleted' => 0, 'projects' => '15')
);

foreach($repos as $repo) {
    $tester->dao->insert(TABLE_REPO)->data($repo)->exec();
}

// 插入测试用的项目数据
$projects = array(
    array('id' => 101, 'name' => '项目1', 'type' => 'project', 'status' => 'doing', 'deleted' => 0),
    array('id' => 102, 'name' => '项目2', 'type' => 'project', 'status' => 'doing', 'deleted' => 0),
    array('id' => 103, 'name' => '项目3', 'type' => 'project', 'status' => 'doing', 'deleted' => 0),
    array('id' => 104, 'name' => '项目4', 'type' => 'project', 'status' => 'doing', 'deleted' => 0),
    array('id' => 105, 'name' => '项目5', 'type' => 'project', 'status' => 'doing', 'deleted' => 0)
);

foreach($projects as $project) {
    $tester->dao->insert(TABLE_PROJECT)->data($project)->exec();
}

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$repoTest = new repoModelTest();

// 5. 测试步骤（必须包含至少5个测试步骤）
$tester->session->set('repoID', 1);
r($repoTest->setHideMenuTest('execution', 101)) && p() && e('101'); // 步骤1：execution环境下有Gitlab代码库时返回对象ID

$tester->session->set('repoID', 0);
r($repoTest->setHideMenuTest('execution', 102)) && p() && e('102'); // 步骤2：execution环境下无代码库时返回对象ID

$tester->session->set('repoID', 2);
r($repoTest->setHideMenuTest('project', 103)) && p() && e('103'); // 步骤3：project环境下有代码库时返回对象ID

$tester->session->set('repoID', 3);
r($repoTest->setHideMenuTest('waterfall', 104)) && p() && e('104'); // 步骤4：waterfall环境下有代码库时返回对象ID

$tester->session->set('repoID', 5);
r($repoTest->setHideMenuTest('execution', 105)) && p() && e('105'); // 步骤5：execution环境下有多个不同类型代码库时返回对象ID