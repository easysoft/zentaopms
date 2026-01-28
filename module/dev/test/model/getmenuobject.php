#!/usr/bin/env php
<?php

/**

title=测试 devModel::getMenuObject();
timeout=0
cid=16005

- 执行devTest模块的getMenuObjectTest方法，参数是'用户管理', 'user', 'browse' 属性module @user
- 执行devTest模块的getMenuObjectTest方法，参数是'任务列表', 'task', 'browse', true 属性active @1
- 执行devTest模块的getMenuObjectTest方法，参数是'项目概览', 'project', 'index', false, array 属性key @xiangmugailan
- 执行devTest模块的getMenuObjectTest方法，参数是'默认标题', 'default', 'action' 属性method @action
- 执行devTest模块的getMenuObjectTest方法，参数是'测试-123', 'test_module', 'test-method' 属性title @测试-123

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$devTest = new devModelTest();

r($devTest->getMenuObjectTest('用户管理', 'user', 'browse')) && p('module') && e('user');
r($devTest->getMenuObjectTest('任务列表', 'task', 'browse', true)) && p('active') && e('1');
r($devTest->getMenuObjectTest('项目概览', 'project', 'index', false, array('项目概览' => 'xiangmugailan'))) && p('key') && e('xiangmugailan');
r($devTest->getMenuObjectTest('默认标题', 'default', 'action')) && p('method') && e('action');
r($devTest->getMenuObjectTest('测试-123', 'test_module', 'test-method')) && p('title') && e('测试-123');