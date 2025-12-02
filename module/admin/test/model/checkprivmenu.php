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

r($adminTest->checkPrivMenuTest()) && p('success,hasMenuList') && e('1,1'); // 测试步骤1:基本菜单权限检查功能
r($adminTest->checkPrivMenuTest()) && p('hasOrderAttribute') && e('1'); // 测试步骤2:验证所有菜单项都有order属性
r($adminTest->checkPrivMenuTest()) && p('hasDisabledAttribute') && e('1'); // 测试步骤3:验证所有菜单项都有disabled属性
r($adminTest->checkPrivMenuTest()) && p('isSorted') && e('1'); // 测试步骤4:验证菜单按order排序
r($adminTest->checkPrivMenuTest()) && p('success') && e('1'); // 测试步骤5:验证菜单列表为对象类型