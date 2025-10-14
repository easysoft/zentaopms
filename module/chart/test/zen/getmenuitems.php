#!/usr/bin/env php
<?php

/**

title=测试 chartZen::getMenuItems();
timeout=0
cid=0

- 执行chartTest模块的getMenuItemsTest方法，参数是$normalMenus  @2
- 执行chartTest模块的getMenuItemsTest方法，参数是$parentOnlyMenus  @0
- 执行chartTest模块的getMenuItemsTest方法，参数是$mixedMenus  @2
- 执行chartTest模块的getMenuItemsTest方法，参数是$emptyMenus  @0
- 执行chartTest模块的getMenuItemsTest方法，参数是$singleChildMenus  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

su('admin');

$chartTest = new chartTest();

// 测试步骤1：正常菜单数据包含子菜单项
$menu1 = new stdClass();
$menu1->id = 1;
$menu1->parent = 0;
$menu1->name = 'parent1';

$menu2 = new stdClass();
$menu2->id = 2;
$menu2->parent = 1;
$menu2->name = 'child1';

$menu3 = new stdClass();
$menu3->id = 3;
$menu3->parent = 1;
$menu3->name = 'child2';

$normalMenus = array($menu1, $menu2, $menu3);
r(count($chartTest->getMenuItemsTest($normalMenus))) && p() && e(2);

// 测试步骤2：全是父菜单项的情况
$parentMenu1 = new stdClass();
$parentMenu1->id = 1;
$parentMenu1->parent = 0;
$parentMenu1->name = 'parent1';

$parentMenu2 = new stdClass();
$parentMenu2->id = 2;
$parentMenu2->parent = 0;
$parentMenu2->name = 'parent2';

$parentOnlyMenus = array($parentMenu1, $parentMenu2);
r(count($chartTest->getMenuItemsTest($parentOnlyMenus))) && p() && e(0);

// 测试步骤3：混合父菜单和子菜单的情况
$mixedParent = new stdClass();
$mixedParent->id = 1;
$mixedParent->parent = 0;
$mixedParent->name = 'parent';

$mixedChild1 = new stdClass();
$mixedChild1->id = 2;
$mixedChild1->parent = 1;
$mixedChild1->name = 'child1';

$mixedChild2 = new stdClass();
$mixedChild2->id = 3;
$mixedChild2->parent = 2;
$mixedChild2->name = 'child2';

$mixedMenus = array($mixedParent, $mixedChild1, $mixedChild2);
r(count($chartTest->getMenuItemsTest($mixedMenus))) && p() && e(2);

// 测试步骤4：空菜单数组的情况
$emptyMenus = array();
r(count($chartTest->getMenuItemsTest($emptyMenus))) && p() && e(0);

// 测试步骤5：单个子菜单项的情况
$singleChild = new stdClass();
$singleChild->id = 1;
$singleChild->parent = 2;
$singleChild->name = 'singleChild';

$singleChildMenus = array($singleChild);
r(count($chartTest->getMenuItemsTest($singleChildMenus))) && p() && e(1);