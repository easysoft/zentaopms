#!/usr/bin/env php
<?php

/**

title=测试 adminModel::setMenu();
timeout=0
cid=14983

- 步骤1：admin模块在system组中的方法测试属性hasSwitcherMenu @1
- 步骤2：user模块在company组中的方法测试属性hasSwitcherMenu @1
- 步骤3：custom模块在feature组中的特殊方法测试属性hasSwitcherMenu @1
- 步骤4：admin模块不在任何组中的方法测试属性hasSwitcherMenu @~~
- 步骤5：不存在模块的处理测试属性hasSwitcherMenu @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（管理员权限）
su('admin');

// 3. 创建测试实例
$adminTest = new adminModelTest();

// 4. 必须包含至少5个测试步骤
r($adminTest->setMenuTest('admin', 'safe', array())) && p('hasSwitcherMenu') && e('1'); // 步骤1：admin模块在system组中的方法测试
r($adminTest->setMenuTest('user', 'browse', array())) && p('hasSwitcherMenu') && e('1'); // 步骤2：user模块在company组中的方法测试
r($adminTest->setMenuTest('custom', 'required', array('product'))) && p('hasSwitcherMenu') && e('1'); // 步骤3：custom模块在feature组中的特殊方法测试
r($adminTest->setMenuTest('admin', 'index', array())) && p('hasSwitcherMenu') && e('~~'); // 步骤4：admin模块不在任何组中的方法测试
r($adminTest->setMenuTest('nonexistent', 'test', array())) && p('hasSwitcherMenu') && e('~~'); // 步骤5：不存在模块的处理测试
