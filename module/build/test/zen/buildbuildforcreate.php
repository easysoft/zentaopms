#!/usr/bin/env php
<?php

/**

title=测试 buildZen::buildBuildForCreate();
timeout=0
cid=0

- 执行buildTest模块的buildBuildForCreateTest方法，参数是array 
 - 属性name @TestBuild001
 - 属性builder @admin
 - 属性createdBy @admin
- 执行buildTest模块的buildBuildForCreateTest方法，参数是array 属性execution @101
- 执行buildTest模块的buildBuildForCreateTest方法，参数是array 属性system @99
- 执行buildTest模块的buildBuildForCreateTest方法，参数是array 属性name @Build001
- 执行buildTest模块的buildBuildForCreateTest方法，参数是array 属性name @Build001

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

$build = zenData('build');
$build->id->range('1-5');
$build->project->range('11-13');
$build->product->range('1-3');
$build->name->range('Build001,Build002,TestBuild');
$build->builder->range('admin,user1,user2');
$build->system->range('1-3');
$build->gen(5);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('产品A,产品B,产品C');
$product->type->range('normal');
$product->gen(3);

$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,dev1,tester1');
$user->realname->range('管理员,用户1,用户2,开发者1,测试员1');
$user->gen(5);

su('admin');

$buildTest = new buildTest();

r($buildTest->buildBuildForCreateTest(array('name' => 'TestBuild001', 'builder' => 'admin', 'product' => 1))) && p('name,builder,createdBy') && e('TestBuild001,admin,admin');
r($buildTest->buildBuildForCreateTest(array('isIntegrated' => 'yes', 'execution' => 101))) && p('execution') && e('101');
r($buildTest->buildBuildForCreateTest(array('newSystem' => true, 'systemName' => 'NewSystem001', 'product' => 1))) && p('system') && e('99');
r($buildTest->buildBuildForCreateTest(array('system' => 0, 'newSystem' => false))) && p('name') && e('Build001');
r($buildTest->buildBuildForCreateTest(array('newSystem' => true, 'systemName' => '', 'system' => 0))) && p('name') && e('Build001');