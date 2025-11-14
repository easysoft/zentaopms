#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::assignForCreate();
timeout=0
cid=19223

- 执行testtaskTest模块的assignForCreateTest方法，参数是1, 1, 11, 1  @success
- 执行testtaskTest模块的assignForCreateTest方法，参数是1, 0, 0, 0  @success
- 执行testtaskTest模块的assignForCreateTest方法，参数是0, 0, 0, 0  @invalid_product_id
- 执行testtaskTest模块的assignForCreateTest方法，参数是2, 2, 0, 2  @success
- 执行testtaskTest模块的assignForCreateTest方法，参数是3, 3, 13, 3  @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

zenData('product')->loadYaml('testtask_assignforcreate', false, 2)->gen(5);

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->name->range('项目A,项目B,项目C,项目D,项目E');
$projectTable->code->range('PROJ-A,PROJ-B,PROJ-C,PROJ-D,PROJ-E');
$projectTable->type->range('project{3},sprint{1},stage{1}');
$projectTable->multiple->range('1{3},0{2}');
$projectTable->status->range('wait{1},doing{2},suspended{1},closed{1}');
$projectTable->acl->range('open{3},private{1},custom{1}');
$projectTable->deleted->range('0{5}');
$projectTable->gen(5);

$executionTable = zenData('project');
$executionTable->id->range('11-15');
$executionTable->name->range('执行A,执行B,执行C,执行D,执行E');
$executionTable->code->range('EXEC-A,EXEC-B,EXEC-C,EXEC-D,EXEC-E');
$executionTable->type->range('sprint{3},stage{1},kanban{1}');
$executionTable->multiple->range('0{5}');
$executionTable->project->range('1-5');
$executionTable->status->range('wait{1},doing{2},suspended{1},closed{1}');
$executionTable->acl->range('open{3},private{1},custom{1}');
$executionTable->deleted->range('0{5}');
$executionTable->gen(5);

$buildTable = zenData('build');
$buildTable->id->range('1-5');
$buildTable->product->range('1-5');
$buildTable->project->range('1-5');
$buildTable->execution->range('11-15');
$buildTable->name->range('版本1.0,版本1.1,版本1.2,版本2.0,版本2.1');
$buildTable->builder->range('admin,user1,user2,dev,qa');
$buildTable->date->range('`2024-01-01`,`2024-01-15`,`2024-02-01`,`2024-02-15`,`2024-03-01`');
$buildTable->createdBy->range('admin,user1,user2,dev,qa');
$buildTable->deleted->range('0{5}');
$buildTable->gen(5);

$userTable = zenData('user');
$userTable->id->range('1-5');
$userTable->account->range('admin,user1,user2,qa,tester');
$userTable->password->range('123456{5}');
$userTable->realname->range('管理员,用户1,用户2,质量保证,测试员');
$userTable->role->range('admin,dev,qa,qa,test');
$userTable->dept->range('1{3},2{1},3{1}');
$userTable->deleted->range('0{5}');
$userTable->gen(5);

$testreportTable = zenData('testreport');
$testreportTable->id->range('1-3');
$testreportTable->product->range('1-3');
$testreportTable->title->range('测试报告1,测试报告2,测试报告3');
$testreportTable->deleted->range('0{3}');
$testreportTable->gen(3);

su('admin');

$testtaskTest = new testtaskZenTest();

r($testtaskTest->assignForCreateTest(1, 1, 11, 1)) && p() && e('success');
r($testtaskTest->assignForCreateTest(1, 0, 0, 0)) && p() && e('success');
r($testtaskTest->assignForCreateTest(0, 0, 0, 0)) && p() && e('invalid_product_id');
r($testtaskTest->assignForCreateTest(2, 2, 0, 2)) && p() && e('success');
r($testtaskTest->assignForCreateTest(3, 3, 13, 3)) && p() && e('success');