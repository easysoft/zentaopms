#!/usr/bin/env php
<?php

/**

title=测试 userModel::getManageListGroupByType();
timeout=0
cid=0

- 步骤1：管理员programs权限测试第programs条的isAdmin属性 @1
- 步骤2：管理员projects权限测试第projects条的isAdmin属性 @1
- 步骤3：user1 products具体ID权限
 - 第products条的list属性 @1
- 步骤4：无权限用户返回空数组 @0
- 步骤5：user2 executions权限
 - 第executions条的list属性 @17

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('projectadmin');
$table->group->range('1,2,3,4');
$table->account->range('admin,user1,user2,noauth');
$table->programs->range('all,1,2,');
$table->projects->range('all,,1,2');
$table->products->range('1,,1,2');
$table->executions->range('17,,1,2');
$table->gen(4);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$userTest = new userTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($userTest->getManageListGroupByTypeTest('admin')) && p('programs:isAdmin') && e('1'); // 步骤1：管理员programs权限测试
r($userTest->getManageListGroupByTypeTest('admin')) && p('projects:isAdmin') && e('1'); // 步骤2：管理员projects权限测试
r($userTest->getManageListGroupByTypeTest('user1')) && p('products:list') && e('1,'); // 步骤3：user1 products具体ID权限
r($userTest->getManageListGroupByTypeTest('noauth')) && p() && e('0'); // 步骤4：无权限用户返回空数组
r($userTest->getManageListGroupByTypeTest('user2')) && p('executions:list') && e('17,'); // 步骤5：user2 executions权限