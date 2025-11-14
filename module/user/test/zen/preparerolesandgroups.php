#!/usr/bin/env php
<?php

/**

title=测试 userZen::prepareRolesAndGroups();
timeout=0
cid=19679

- 步骤1：正常情况，检查管理员权限组名称第groupList条的1属性 @管理员
- 步骤2：检查管理员角色映射到权限组ID第roleGroup条的admin属性 @1
- 步骤3：检查开发权限组名称第groupList条的2属性 @开发
- 步骤4：检查开发角色映射到权限组ID第roleGroup条的dev属性 @2
- 步骤5：检查测试权限组名称第groupList条的3属性 @测试

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('group');
$table->id->range('1-6');
$table->project->range('0');
$table->vision->range('rnd');
$table->name->range('管理员,开发,测试,产品经理,项目经理,其他');
$table->role->range('admin,dev,qa,po,pm,null');
$table->desc->range('系统管理员,开发人员,测试人员,产品经理,项目经理,其他用户');
$table->gen(6);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$userTest = new userZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($userTest->prepareRolesAndGroupsTest()) && p('groupList:1') && e('管理员'); // 步骤1：正常情况，检查管理员权限组名称
r($userTest->prepareRolesAndGroupsTest()) && p('roleGroup:admin') && e('1'); // 步骤2：检查管理员角色映射到权限组ID
r($userTest->prepareRolesAndGroupsTest()) && p('groupList:2') && e('开发'); // 步骤3：检查开发权限组名称
r($userTest->prepareRolesAndGroupsTest()) && p('roleGroup:dev') && e('2'); // 步骤4：检查开发角色映射到权限组ID
r($userTest->prepareRolesAndGroupsTest()) && p('groupList:3') && e('测试'); // 步骤5：检查测试权限组名称