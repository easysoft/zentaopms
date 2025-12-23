#!/usr/bin/env php
<?php

/**

title=测试 adminModel::checkPrivMenu();
timeout=0
cid=14976

- 测试步骤1:基本菜单权限检查功能
 - 属性success @1
 - 属性hasMenuList @1
- 测试步骤2:验证所有菜单项都有order属性属性hasOrderAttribute @1
- 测试步骤3:验证所有菜单项都有disabled属性属性hasDisabledAttribute @1
- 测试步骤4:验证菜单按order排序属性isSorted @1
- 测试步骤5:验证菜单列表为对象类型属性success @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/admin.unittest.class.php';

su('admin');

$adminTest = new adminTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($adminTest->checkPrivMenuTest()) && p('hasMenuList') && e('1'); // 步骤1：检查是否有菜单列表
r($adminTest->checkPrivMenuTest()) && p('menuCount') && e('11'); // 步骤2：检查菜单数量
r($adminTest->checkPrivMenuTest()) && p('hasLinkedMenu') && e('1'); // 步骤3：检查是否有已链接菜单
r($adminTest->checkPrivMenuTest()) && p('hasDisabledMenu') && e('1'); // 步骤4：检查是否有已禁用菜单
r($adminTest->checkPrivMenuTest()) && p('hasMenuList,menuCount') && e('1,11'); // 步骤5：验证菜单列表结构
