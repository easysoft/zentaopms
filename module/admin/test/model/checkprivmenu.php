#!/usr/bin/env php
<?php

/**

title=测试 adminModel::checkPrivMenu();
timeout=0
cid=14976

- 步骤1：检查菜单列表已生成 @1
- 步骤2：检查菜单总数 @3
- 步骤3：检查可用菜单数量 @2
- 步骤4：检查禁用菜单数量 @1
- 步骤5：检查菜单都包含 order 属性 @1
- 步骤6：检查菜单都包含 disabled 属性 @1
- 步骤7：检查菜单按 order 排序 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$adminTest = new adminModelTest();

r($adminTest->checkPrivMenuTest()) && p('hasMenuList') && e('1');
r($adminTest->checkPrivMenuTest()) && p('menuCount') && e('3');
r($adminTest->checkPrivMenuTest()) && p('enabledMenuCount') && e('2');
r($adminTest->checkPrivMenuTest()) && p('disabledMenuCount') && e('1');
r($adminTest->checkPrivMenuTest()) && p('hasOrderAttribute') && e('1');
r($adminTest->checkPrivMenuTest()) && p('hasDisabledAttribute') && e('1');
r($adminTest->checkPrivMenuTest()) && p('isSorted') && e('1');
