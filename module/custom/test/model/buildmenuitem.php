#!/usr/bin/env php
<?php

/**

title=测试 customModel::buildMenuItem();
timeout=0
cid=15891

- 测试步骤1：分割线菜单项属性type @divider
- 测试步骤2：普通菜单项
 - 属性name @index
 - 属性text @首页
 - 属性link @/index
- 测试步骤3：带子菜单的菜单项
 - 属性name @user
 - 属性text @用户
- 测试步骤4：隐藏菜单项属性hidden @1
- 测试步骤5：教程模式菜单项属性tutorial @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

zenData('user')->gen(5);
su('admin');

$customTester = new customTest();

r($customTester->buildMenuItemTest('-', array(), '', '', '')) && p('type') && e('divider'); // 测试步骤1：分割线菜单项
r($customTester->buildMenuItemTest('index', array(), 'index', '首页', '/index')) && p('name,text,link') && e('index,首页,/index'); // 测试步骤2：普通菜单项
r($customTester->buildMenuItemTest(array('subMenu' => array((object)array('link' => array('module' => 'user', 'method' => 'browse')))), array(), 'user', '用户', '/user')) && p('name,text') && e('user,用户'); // 测试步骤3：带子菜单的菜单项
r($customTester->buildMenuItemTest('hidden', array('hidden' => (object)array('hidden' => true)), 'hidden', '隐藏', '/hidden')) && p('hidden') && e('1'); // 测试步骤4：隐藏菜单项
r($customTester->buildMenuItemTest('tutorial', array(), 'tutorial', '教程', '/tutorial', true)) && p('tutorial') && e('1'); // 测试步骤5：教程模式菜单项