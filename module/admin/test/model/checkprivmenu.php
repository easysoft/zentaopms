#!/usr/bin/env php
<?php

/**

title=测试 adminModel::checkPrivMenu();
timeout=0
cid=0

- 步骤1：检查是否有菜单列表属性hasMenuList @1
- 步骤2：检查菜单数量属性menuCount @11
- 步骤3：检查是否有已链接菜单属性hasLinkedMenu @1
- 步骤4：检查是否有已禁用菜单属性hasDisabledMenu @1
- 步骤5：验证菜单列表结构
 - 属性hasMenuList @1
 - 属性menuCount @11

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/admin.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$adminTest = new adminTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($adminTest->checkPrivMenuTest()) && p('hasMenuList') && e('1'); // 步骤1：检查是否有菜单列表
r($adminTest->checkPrivMenuTest()) && p('menuCount') && e('11'); // 步骤2：检查菜单数量
r($adminTest->checkPrivMenuTest()) && p('hasLinkedMenu') && e('1'); // 步骤3：检查是否有已链接菜单
r($adminTest->checkPrivMenuTest()) && p('hasDisabledMenu') && e('1'); // 步骤4：检查是否有已禁用菜单
r($adminTest->checkPrivMenuTest()) && p('hasMenuList,menuCount') && e('1,11'); // 步骤5：验证菜单列表结构
