#!/usr/bin/env php
<?php

/**

title=测试 programModel::checkPriv();
timeout=0
cid=17676

- 测试步骤1:programID为0时应返回false @0
- 测试步骤2:admin用户访问存在的programID应返回true @1
- 测试步骤3:普通用户访问有权限的programID应返回true @1
- 测试步骤4:普通用户访问无权限的programID应返回false @0
- 测试步骤5:admin用户访问负数programID应返回true @1
- 测试步骤6:普通用户访问负数programID但无权限应返回false @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';

$program = zenData('project');
$program->id->range('1-5');
$program->name->range('项目集1,项目集2,项目集3,项目集4,项目集5');
$program->type->range('program');
$program->status->range('wait');
$program->parent->range('0');
$program->grade->range('1');
$program->path->range('1,2,3,4,5')->prefix(',')->postfix(',');
$program->deleted->range('0');
$program->gen(5);

global $tester;
$programTester = new programTest();

su('admin');
r($programTester->checkPrivTest(0)) && p() && e('0'); // 测试步骤1:programID为0时应返回false
r($programTester->checkPrivTest(1)) && p() && e('1'); // 测试步骤2:admin用户访问存在的programID应返回true

su('user1');
$tester->app->user->view->programs = '1,2,3';
r($programTester->checkPrivTest(2)) && p() && e('1'); // 测试步骤3:普通用户访问有权限的programID应返回true
r($programTester->checkPrivTest(5)) && p() && e('0'); // 测试步骤4:普通用户访问无权限的programID应返回false

su('admin');
r($programTester->checkPrivTest(-1)) && p() && e('1'); // 测试步骤5:admin用户访问负数programID应返回true

su('user1');
$tester->app->user->view->programs = '1,2,3';
r($programTester->checkPrivTest(-1)) && p() && e('0'); // 测试步骤6:普通用户访问负数programID但无权限应返回false