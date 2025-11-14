#!/usr/bin/env php
<?php

/**

title=测试 chartZen::getMenuItems();
timeout=0
cid=15586

- 测试空数组 @0
- 测试仅包含parent为0的菜单 @0
- 测试仅包含子菜单 @3
- 测试混合菜单 @2
- 测试多级菜单并验证第一个子菜单项
 - 第0条的id属性 @2
 - 第0条的parent属性 @1
 - 第0条的name属性 @SubMenu1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$chartTest = new chartZenTest();

r(count($chartTest->getMenuItemsTest(array()))) && p() && e('0'); // 测试空数组
r(count($chartTest->getMenuItemsTest(array((object)array('id' => 1, 'parent' => 0, 'name' => 'Menu1'), (object)array('id' => 2, 'parent' => 0, 'name' => 'Menu2'))))) && p() && e('0'); // 测试仅包含parent为0的菜单
r(count($chartTest->getMenuItemsTest(array((object)array('id' => 1, 'parent' => 5, 'name' => 'SubMenu1'), (object)array('id' => 2, 'parent' => 5, 'name' => 'SubMenu2'), (object)array('id' => 3, 'parent' => 6, 'name' => 'SubMenu3'))))) && p() && e('3'); // 测试仅包含子菜单
r(count($chartTest->getMenuItemsTest(array((object)array('id' => 1, 'parent' => 0, 'name' => 'Menu1'), (object)array('id' => 2, 'parent' => 1, 'name' => 'SubMenu1'), (object)array('id' => 3, 'parent' => 1, 'name' => 'SubMenu2'))))) && p() && e('2'); // 测试混合菜单
r($chartTest->getMenuItemsTest(array((object)array('id' => 1, 'parent' => 0, 'name' => 'Menu1'), (object)array('id' => 2, 'parent' => 1, 'name' => 'SubMenu1'), (object)array('id' => 3, 'parent' => 1, 'name' => 'SubMenu2'), (object)array('id' => 4, 'parent' => 2, 'name' => 'SubMenu3')))) && p('0:id,parent,name') && e('2,1,SubMenu1'); // 测试多级菜单并验证第一个子菜单项