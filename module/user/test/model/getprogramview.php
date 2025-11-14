#!/usr/bin/env php
<?php

/**

title=测试 userModel::getProgramView();
timeout=0
cid=19628

- 执行userTest模块的getProgramViewTest方法，参数是'admin', $allPrograms, $manageObjectsAdmin, array  @1,2,3,4,5,6,7,8

- 执行userTest模块的getProgramViewTest方法，参数是'user4', $allPrograms, $manageObjectsUser, array  @1,2,3

- 执行userTest模块的getProgramViewTest方法，参数是'user1', $allPrograms, $manageObjectsUser, array  @1,2,3,4,5

- 执行userTest模块的getProgramViewTest方法，参数是'user3', $allPrograms, $manageObjectsUser, array  @1,2,3,5,6,7,8

- 执行userTest模块的getProgramViewTest方法，参数是'user4', $allPrograms, $manageObjectsUser, $stakeholders, array  @1,2,3,6,7

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('project');
$table->loadYaml('project_getprogramview', false, 2);
$table->gen(10);

// 设置公司管理员（用于权限检查）
global $app;
if(!isset($app->company)) $app->company = new stdClass();
$app->company->admins = ',admin,';

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$userTest = new userTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 准备测试数据
$allPrograms = array();
for($i = 1; $i <= 8; $i++)
{
    $program = new stdClass();
    $program->id = $i;
    $program->type = 'program';
    $program->acl = ($i <= 3) ? 'open' : (($i <= 5) ? 'private' : 'program');
    $program->PM = ($i <= 3) ? 'admin' : (($i <= 5) ? 'user1' : 'user2');
    $program->openedBy = ($i <= 2) ? 'admin' : (($i <= 4) ? 'user1' : 'user3');
    $program->parent = 0;
    $program->path = ",$i,";
    $allPrograms[$i] = $program;
}

// 测试步骤1：管理员用户可查看所有项目集
$manageObjectsAdmin = array('programs' => array('isAdmin' => true));
r($userTest->getProgramViewTest('admin', $allPrograms, $manageObjectsAdmin, array(), array(), array())) && p() && e('1,2,3,4,5,6,7,8');

// 测试步骤2：普通用户查看公开项目集
$manageObjectsUser = array('programs' => array('isAdmin' => false));
r($userTest->getProgramViewTest('user4', $allPrograms, $manageObjectsUser, array(), array(), array())) && p() && e('1,2,3');

// 测试步骤3：项目集PM查看自己管理的项目集
r($userTest->getProgramViewTest('user1', $allPrograms, $manageObjectsUser, array(), array(), array())) && p() && e('1,2,3,4,5');

// 测试步骤4：项目集创建者查看自己创建的项目集
r($userTest->getProgramViewTest('user3', $allPrograms, $manageObjectsUser, array(), array(), array())) && p() && e('1,2,3,5,6,7,8');

// 测试步骤5：干系人查看项目集
$stakeholders = array('program' => array(6 => array('user4' => 'user4'), 7 => array('user4' => 'user4')));
r($userTest->getProgramViewTest('user4', $allPrograms, $manageObjectsUser, $stakeholders, array(), array())) && p() && e('1,2,3,6,7');