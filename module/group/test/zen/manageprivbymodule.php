#!/usr/bin/env php
<?php

/**

title=测试 groupZen::managePrivByModule();
timeout=0
cid=0

- 步骤1：验证title类型属性title @string
- 步骤2：验证groups数量属性groups @5
- 步骤3：验证subsets数量属性subsets @77
- 步骤4：验证packages数量属性packages @77
- 步骤5：验证privs数量属性privs @18
- 步骤6：再次验证title类型属性title @string
- 步骤7：再次验证groups数量属性groups @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/groupzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('group');
$table->id->range('1-10');
$table->name->range('管理员,开发,测试,产品,项目经理{5}');
$table->role->range('admin,dev,qa,po,pm{5}');
$table->acl->range('open,custom{9}');
$table->gen(5);

$userGroup = zenData('usergroup');
$userGroup->account->range('admin,user1,user2,user3,user4{5}');
$userGroup->group->range('1-5');
$userGroup->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$groupZenTest = new groupZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($groupZenTest->managePrivByModuleTest()) && p('title') && e('string'); // 步骤1：验证title类型
r($groupZenTest->managePrivByModuleTest()) && p('groups') && e('5'); // 步骤2：验证groups数量
r($groupZenTest->managePrivByModuleTest()) && p('subsets') && e('77'); // 步骤3：验证subsets数量
r($groupZenTest->managePrivByModuleTest()) && p('packages') && e('77'); // 步骤4：验证packages数量
r($groupZenTest->managePrivByModuleTest()) && p('privs') && e('18'); // 步骤5：验证privs数量
r($groupZenTest->managePrivByModuleTest()) && p('title') && e('string'); // 步骤6：再次验证title类型
r($groupZenTest->managePrivByModuleTest()) && p('groups') && e('5'); // 步骤7：再次验证groups数量