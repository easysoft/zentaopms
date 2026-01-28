#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildProjectRoleActorData();
timeout=0
cid=15824

- 步骤1：完整数据输入所有字段属性id @1001
- 步骤2：缺少pid字段默认值属性pid @~~
- 步骤3：包含pid字段值设置属性pid @P001
- 步骤4：角色类型字段验证属性roletype @unknown-role
- 步骤5：角色参数字段验证属性roletypeparameter @manager

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTaoTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($convertTest->buildProjectRoleActorDataTest(array('id' => 1001, 'pid' => '2001', 'roletype' => 'atlassian-user-role-actor', 'roletypeparameter' => 'admin'))) && p('id') && e('1001'); // 步骤1：完整数据输入所有字段
r($convertTest->buildProjectRoleActorDataTest(array('id' => 1002, 'roletype' => 'atlassian-group-role-actor', 'roletypeparameter' => 'developers'))) && p('pid') && e('~~'); // 步骤2：缺少pid字段默认值
r($convertTest->buildProjectRoleActorDataTest(array('id' => 1003, 'pid' => 'P001', 'roletype' => 'atlassian-user-role-actor', 'roletypeparameter' => 'testuser'))) && p('pid') && e('P001'); // 步骤3：包含pid字段值设置
r($convertTest->buildProjectRoleActorDataTest(array('id' => 1004, 'roletype' => 'unknown-role', 'roletypeparameter' => 'guest'))) && p('roletype') && e('unknown-role'); // 步骤4：角色类型字段验证
r($convertTest->buildProjectRoleActorDataTest(array('id' => 1005, 'pid' => '', 'roletype' => 'custom-role-actor', 'roletypeparameter' => 'manager'))) && p('roletypeparameter') && e('manager'); // 步骤5：角色参数字段验证