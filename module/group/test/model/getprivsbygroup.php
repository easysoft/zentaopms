#!/usr/bin/env php
<?php

/**

title=测试 groupModel::getPrivsByGroup();
timeout=0
cid=16710

- 步骤1：正常分组权限查询（分组1有3个权限） @3
- 步骤2：不存在的分组ID查询 @0
- 步骤3：无效分组ID查询（0） @0
- 步骤4：负数分组ID查询 @0
- 步骤5：权限格式验证属性user-browse @user-browse
- 步骤6：多权限分组查询（分组2有2个权限） @2
- 步骤7：空权限分组查询 @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$table = zenData('group');
$table->name->range('管理组,开发组,测试组,项目组,空权限组');
$table->role->range('admin,dev,qa,pm,limited');
$table->gen(5);

$privTable = zenData('grouppriv');
$privTable->group->range('1{3},2{2},3{1}');
$privTable->module->range('user,task,project,admin,bug,story');
$privTable->method->range('browse,create,edit,delete,view,manage');
$privTable->gen(6);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$groupTest = new groupModelTest();

// 5. 执行至少7个测试步骤
r(count($groupTest->getPrivsByGroupTest(1))) && p() && e('3');                        // 步骤1：正常分组权限查询（分组1有3个权限）
r(count($groupTest->getPrivsByGroupTest(999))) && p() && e('0');                      // 步骤2：不存在的分组ID查询
r(count($groupTest->getPrivsByGroupTest(0))) && p() && e('0');                        // 步骤3：无效分组ID查询（0）
r(count($groupTest->getPrivsByGroupTest(-1))) && p() && e('0');                       // 步骤4：负数分组ID查询
r($groupTest->getPrivsByGroupTest(1)) && p('user-browse') && e('user-browse');        // 步骤5：权限格式验证
r(count($groupTest->getPrivsByGroupTest(2))) && p() && e('2');                        // 步骤6：多权限分组查询（分组2有2个权限）
r(count($groupTest->getPrivsByGroupTest(5))) && p() && e('0');                        // 步骤7：空权限分组查询