#!/usr/bin/env php
<?php

/**

title=测试 groupModel::insertPrivs();
timeout=0
cid=16718

- 执行groupTest模块的insertPrivsTest方法，参数是$insertPrivs  @1
- 执行groupTest模块的insertPrivsTest方法，参数是$newGroupPrivs  @1
- 执行groupTest模块的insertPrivsTest方法，参数是$emptyPrivs  @1
- 执行groupTest模块的insertPrivsTest方法，参数是$duplicatePrivs  @1
- 执行groupTest模块的insertPrivsTest方法，参数是$multiPrivs  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/group.unittest.class.php';

$table = zenData('group');
$table->id->range('1-10');
$table->name->range('admin,test{9}');
$table->role->range('admin,limited{9}');
$table->gen(10);

$table = zenData('grouppriv');
$table->group->range('1-4');
$table->module->range('module1,module2,module3,module4');
$table->method->range('method1,method2,method3,method4');
$table->gen(4);

su('admin');

$groupTest = new groupTest();

// 测试步骤1：已有权限的分组新增一个权限
$insertPrivs = array((object)array('group' => 1, 'module' => 'project', 'method' => 'create'));
r($groupTest->insertPrivsTest($insertPrivs)) && p() && e('1');

// 测试步骤2：新分组插入权限数据
$newGroupPrivs = array((object)array('group' => 10, 'module' => 'user', 'method' => 'view'));
r($groupTest->insertPrivsTest($newGroupPrivs)) && p() && e('1');

// 测试步骤3：插入空数组测试
$emptyPrivs = array();
r($groupTest->insertPrivsTest($emptyPrivs)) && p() && e('1');

// 测试步骤4：插入重复权限验证去重功能
$duplicatePrivs = array((object)array('group' => 1, 'module' => 'project', 'method' => 'create'));
r($groupTest->insertPrivsTest($duplicatePrivs)) && p() && e('1');

// 测试步骤5：插入多个分组的多个权限
$multiPrivs = array(
    (object)array('group' => 6, 'module' => 'task', 'method' => 'create'),
    (object)array('group' => 6, 'module' => 'task', 'method' => 'edit'),
    (object)array('group' => 7, 'module' => 'bug', 'method' => 'resolve')
);
r($groupTest->insertPrivsTest($multiPrivs)) && p() && e('1');