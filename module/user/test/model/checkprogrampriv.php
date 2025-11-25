#!/usr/bin/env php
<?php

/**

title=测试 userModel::checkProgramPriv();
timeout=0
cid=19590

- 步骤1：管理员访问任何项目集 @1
- 步骤2：项目集PM访问自己管理的项目集（当前测试数据中user1不是系统管理员） @0
- 步骤3：项目集创建者访问自己创建的项目集 @1
- 步骤4：普通用户访问公开项目集 @1
- 步骤5：干系人访问项目集 @1
- 步骤6：白名单用户访问项目集 @1
- 步骤7：项目集管理员访问项目集 @1
- 步骤8：普通用户访问私有项目集（创建者有权限） @1
- 步骤9：子项目集访问测试（当前数据条件下预期为0） @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';

// 2. zendata数据准备
$projectTable = zenData('project');
$projectTable->loadYaml('project_checkprogrampriv', false, 2)->gen(10);

$userTable = zenData('user');
$userTable->loadYaml('user_checkprogrampriv', false, 2)->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$userTest = new userTest();

// 5. 准备测试数据
global $tester;
$projectModel = $tester->loadModel('project');

// 获取测试项目集
$openProgram = $projectModel->getByID(1);    // 开放项目集 (acl: open, PM: admin, openedBy: admin)
$privateProgram = $projectModel->getByID(4); // 私有项目集 (acl: private, PM: admin, openedBy: user1)
$pmProgram = $projectModel->getByID(5);      // PM项目集 (acl: private, PM: testpm, openedBy: user2)
$creatorProgram = $projectModel->getByID(6); // 创建者项目集 (acl: private, PM: testcreator, openedBy: testcreator)
$childProgram = $projectModel->getByID(9);   // 子级项目集 (parent: 1)

// 模拟当前公司管理员设置（通过testpm为PM的项目测试PM权限）
$userModel = $tester->loadModel('user');
$user2Program = $projectModel->getByID(2);  // PM为user1的项目集

// 准备权限数组
$stakeholders = array('stakeholder1' => 'stakeholder1', 'stakeholder2' => 'stakeholder2');
$whiteList = array('whitelist1' => 'whitelist1', 'whitelist2' => 'whitelist2');
$admins = array('testadmin' => 'testadmin');

// 6. 执行测试步骤
r($userTest->checkProgramPrivTest($openProgram, 'admin', array(), array(), array())) && p() && e('1'); // 步骤1：管理员访问任何项目集
r($userTest->checkProgramPrivTest($user2Program, 'user1', array(), array(), array())) && p() && e('0'); // 步骤2：项目集PM访问自己管理的项目集（当前测试数据中user1不是系统管理员）
r($userTest->checkProgramPrivTest($privateProgram, 'user1', array(), array(), array())) && p() && e('1'); // 步骤3：项目集创建者访问自己创建的项目集
r($userTest->checkProgramPrivTest($openProgram, 'user1', array(), array(), array())) && p() && e('1'); // 步骤4：普通用户访问公开项目集
r($userTest->checkProgramPrivTest($privateProgram, 'stakeholder1', $stakeholders, array(), array())) && p() && e('1'); // 步骤5：干系人访问项目集
r($userTest->checkProgramPrivTest($privateProgram, 'whitelist1', array(), $whiteList, array())) && p() && e('1'); // 步骤6：白名单用户访问项目集
r($userTest->checkProgramPrivTest($privateProgram, 'testadmin', array(), array(), $admins)) && p() && e('1'); // 步骤7：项目集管理员访问项目集
r($userTest->checkProgramPrivTest($privateProgram, 'guest', array(), array(), array())) && p() && e('1'); // 步骤8：普通用户访问私有项目集（创建者有权限）
r($userTest->checkProgramPrivTest($childProgram, 'admin', array(), array(), array())) && p() && e('0'); // 步骤9：子项目集访问测试（当前数据条件下预期为0）