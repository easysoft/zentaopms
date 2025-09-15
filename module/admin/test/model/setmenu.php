#!/usr/bin/env php
<?php

/**

title=测试 adminModel::setMenu();
timeout=0
cid=0

- 步骤1：正常admin模块菜单设置属性hasSwitcherMenu @1
- 步骤2：user模块菜单设置属性hasSwitcherMenu @1
- 步骤3：custom模块特殊方法测试属性hasSwitcherMenu @1
- 步骤4：project模块菜单设置属性hasSwitcherMenu @1
- 步骤5：不存在模块的处理属性hasSwitcherMenu @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/admin.unittest.class.php';

// 2. 用户登录（管理员权限）
su('admin');

// 3. 创建测试实例
$adminTest = new adminTest();

// 4. 必须包含至少5个测试步骤
r($adminTest->setMenuTest('admin', 'index', array())) && p('hasSwitcherMenu') && e('1'); // 步骤1：正常admin模块菜单设置
r($adminTest->setMenuTest('user', 'browse', array())) && p('hasSwitcherMenu') && e('1'); // 步骤2：user模块菜单设置
r($adminTest->setMenuTest('custom', 'required', array('product'))) && p('hasSwitcherMenu') && e('1'); // 步骤3：custom模块特殊方法测试
r($adminTest->setMenuTest('project', 'browse', array())) && p('hasSwitcherMenu') && e('1'); // 步骤4：project模块菜单设置
r($adminTest->setMenuTest('nonexistent', 'test', array())) && p('hasSwitcherMenu') && e('~~'); // 步骤5：不存在模块的处理