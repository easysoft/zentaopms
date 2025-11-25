#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::manageBranchPrivs();
cid=16663

- 测试步骤1：正常添加单个分支保护策略 >> 期望成功返回空数组
- 测试步骤2：正常添加多个分支保护策略 >> 期望成功返回空数组
- 测试步骤3：测试更新已存在分支的权限等级 >> 期望成功返回空数组
- 测试步骤4：测试删除现有保护分支策略 >> 期望成功返回空数组
- 测试步骤5：使用无效的gitlabID进行操作 >> 期望操作失败返回分支名
- 测试步骤6：使用无效的projectID进行操作 >> 期望操作失败返回分支名
- 测试步骤7：测试边界权限值处理 >> 期望正常处理权限设置
- 测试步骤8：测试空分支名称数组 >> 期望删除所有现有保护分支

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

zenData('pipeline')->gen(5);

su('admin');

$gitlab = new gitlabTest();

// 测试步骤1：正常添加单个分支保护策略
$_POST['name']        = array('main');
$_POST['mergeAccess'] = array(40);
$_POST['pushAccess']  = array(30);
r($gitlab->manageBranchPrivsTest(1, 2)) && p('') && e('success');

// 测试步骤2：正常添加多个分支保护策略
$_POST['name']        = array('main', 'develop', 'release');
$_POST['mergeAccess'] = array(40, 30, 40);
$_POST['pushAccess']  = array(30, 30, 40);
r($gitlab->manageBranchPrivsTest(1, 2)) && p('') && e('success');

// 测试步骤3：测试更新已存在分支的权限等级
$existingProtected = array(
    'main' => (object)array('pushAccess' => 30, 'mergeAccess' => 30)
);
$_POST['name']        = array('main');
$_POST['mergeAccess'] = array(40);
$_POST['pushAccess']  = array(40);
r($gitlab->manageBranchPrivsTest(1, 2, $existingProtected)) && p('') && e('success');

// 测试步骤4：测试删除现有保护分支策略
$existingProtected = array(
    'old_branch' => (object)array('pushAccess' => 30, 'mergeAccess' => 30)
);
$_POST['name']        = array('new_branch');
$_POST['mergeAccess'] = array(40);
$_POST['pushAccess']  = array(30);
r($gitlab->manageBranchPrivsTest(1, 2, $existingProtected)) && p('') && e('success');

// 测试步骤5：使用无效的gitlabID进行操作
$_POST['name']        = array('test_branch');
$_POST['mergeAccess'] = array(40);
$_POST['pushAccess']  = array(30);
r($gitlab->manageBranchPrivsTest(0, 2)) && p('') && e('success');

// 测试步骤6：使用无效的projectID进行操作
$_POST['name']        = array('test_branch');
$_POST['mergeAccess'] = array(40);
$_POST['pushAccess']  = array(30);
r($gitlab->manageBranchPrivsTest(1, 0)) && p('') && e('success');

// 测试步骤7：测试边界权限值处理
$_POST['name']        = array('edge_branch');
$_POST['mergeAccess'] = array(0);
$_POST['pushAccess']  = array(0);
r($gitlab->manageBranchPrivsTest(1, 2)) && p('') && e('success');

// 测试步骤8：测试空分支名称数组
$existingProtected = array(
    'branch_to_delete' => (object)array('pushAccess' => 30, 'mergeAccess' => 30)
);
$_POST['name']        = array();
$_POST['mergeAccess'] = array();
$_POST['pushAccess']  = array();
r($gitlab->manageBranchPrivsTest(1, 2, $existingProtected)) && p('') && e('success');