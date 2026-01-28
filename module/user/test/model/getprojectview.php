#!/usr/bin/env php
<?php

/**

title=测试 userModel::getProjectView();
timeout=0
cid=19631

- 步骤1：管理员用户可访问所有项目 @1,2,3

- 步骤2：普通用户测试开放权限项目访问 @1,2

- 步骤3：普通用户测试私有权限项目访问（无团队权限） @0
- 步骤4：普通用户测试有团队权限的私有项目访问 @1
- 步骤5：普通用户测试有管理权限的项目访问 @1,2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
// 由于测试私有方法不需要数据库数据，此处不需要zendata生成数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$userTest = new userModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($userTest->getProjectViewTest('admin', array(1 => (object)array('id' => 1, 'acl' => 'open', 'PO' => '', 'QD' => '', 'RD' => '', 'PM' => '', 'type' => 'project', 'parent' => 0, 'path' => ',1,'), 2 => (object)array('id' => 2, 'acl' => 'open', 'PO' => '', 'QD' => '', 'RD' => '', 'PM' => '', 'type' => 'project', 'parent' => 0, 'path' => ',2,'), 3 => (object)array('id' => 3, 'acl' => 'private', 'PO' => '', 'QD' => '', 'RD' => '', 'PM' => '', 'type' => 'project', 'parent' => 0, 'path' => ',3,')), array('projects' => array('isAdmin' => 1)), array(), array(), array(), array())) && p() && e('1,2,3'); // 步骤1：管理员用户可访问所有项目
r($userTest->getProjectViewTest('user1', array(1 => (object)array('id' => 1, 'acl' => 'open', 'PO' => '', 'QD' => '', 'RD' => '', 'PM' => '', 'type' => 'project', 'parent' => 0, 'path' => ',1,'), 2 => (object)array('id' => 2, 'acl' => 'open', 'PO' => '', 'QD' => '', 'RD' => '', 'PM' => '', 'type' => 'project', 'parent' => 0, 'path' => ',2,')), array('projects' => array('isAdmin' => 0)), array(), array(), array(), array())) && p() && e('1,2'); // 步骤2：普通用户测试开放权限项目访问
r($userTest->getProjectViewTest('user1', array(1 => (object)array('id' => 1, 'acl' => 'private', 'PO' => '', 'QD' => '', 'RD' => '', 'PM' => '', 'type' => 'project', 'parent' => 0, 'path' => ',1,')), array('projects' => array('isAdmin' => 0)), array(), array(), array(), array())) && p() && e('0'); // 步骤3：普通用户测试私有权限项目访问（无团队权限）
r($userTest->getProjectViewTest('user1', array(1 => (object)array('id' => 1, 'acl' => 'private', 'PO' => '', 'QD' => '', 'RD' => '', 'PM' => '', 'type' => 'project', 'parent' => 0, 'path' => ',1,')), array('projects' => array('isAdmin' => 0)), array('project' => array(1 => array('user1' => 'user1'))), array(), array(), array())) && p() && e('1'); // 步骤4：普通用户测试有团队权限的私有项目访问
r($userTest->getProjectViewTest('user1', array(1 => (object)array('id' => 1, 'acl' => 'private', 'PO' => '', 'QD' => '', 'RD' => '', 'PM' => '', 'type' => 'project', 'parent' => 0, 'path' => ',1,'), 2 => (object)array('id' => 2, 'acl' => 'private', 'PO' => '', 'QD' => '', 'RD' => '', 'PM' => '', 'type' => 'project', 'parent' => 0, 'path' => ',2,')), array('projects' => array('isAdmin' => 0, 'list' => '1,2')), array(), array(), array(), array())) && p() && e('1,2'); // 步骤5：普通用户测试有管理权限的项目访问