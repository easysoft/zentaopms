#!/usr/bin/env php
<?php

/**

title=测试 searchTao::checkObjectPriv();
timeout=0
cid=18317

- 测试product类型权限检查,有权限的产品应保留 >> 期望返回1个结果
- 测试product类型权限检查,无权限的产品应移除 >> 期望返回0个结果
- 测试product类型权限检查,shadow产品应移除 >> 期望返回0个结果
- 测试program类型权限检查,有权限的项目集应保留 >> 期望返回1个结果
- 测试project类型权限检查,无权限的项目应移除 >> 期望返回0个结果
- 测试execution类型权限检查,有权限的执行应保留 >> 期望返回1个结果
- 测试doc类型权限检查,有权限的文档应保留 >> 期望返回1个结果
- 测试todo类型权限检查,私有待办应移除 >> 期望返回0个结果
- 测试testsuite类型权限检查,私有套件应移除 >> 期望返回0个结果
- 测试未知objectType,应返回原结果 >> 期望返回1个结果

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('Product 1,Product 2,Product 3,Product 4,Product 5,Product 6,Product 7,Product 8,Product 9,Product 10');
$product->shadow->range('0{9},1');
$product->gen(10);

$program = zenData('project');
$program->id->range('1-5');
$program->name->range('Program 1,Program 2,Program 3,Program 4,Program 5');
$program->type->range('program');
$program->gen(5);

$project = zenData('project');
$project->id->range('6-10');
$project->name->range('Project 1,Project 2,Project 3,Project 4,Project 5');
$project->type->range('project');
$project->gen(5);

$execution = zenData('project');
$execution->id->range('11-15');
$execution->name->range('Execution 1,Execution 2,Execution 3,Execution 4,Execution 5');
$execution->type->range('sprint');
$execution->gen(5);

$doc = zenData('doc');
$doc->id->range('1-5');
$doc->title->range('Doc 1,Doc 2,Doc 3,Doc 4,Doc 5');
$doc->lib->range('1,2,3,4,5');
$doc->deleted->range('0');
$doc->gen(5);

$doclib = zenData('doclib');
$doclib->id->range('1-5');
$doclib->name->range('Lib 1,Lib 2,Lib 3,Lib 4,Lib 5');
$doclib->deleted->range('0');
$doclib->gen(5);

$todo = zenData('todo');
$todo->id->range('1-5');
$todo->name->range('Todo 1,Todo 2,Todo 3,Todo 4,Todo 5');
$todo->private->range('0,0,1,1,1');
$todo->account->range('admin,admin,user1,user2,user3');
$todo->gen(5);

$testsuite = zenData('testsuite');
$testsuite->id->range('1-5');
$testsuite->name->range('Suite 1,Suite 2,Suite 3,Suite 4,Suite 5');
$testsuite->type->range('public,public,private,private,private');
$testsuite->deleted->range('0');
$testsuite->gen(5);

su('admin');

global $app;
$app->user->view = new stdClass();
$app->user->view->products = '1,2,3';
$app->user->view->programs = '1,2,3';
$app->user->view->projects = '6,7,8';
$app->user->view->sprints = '11,12,13';

$searchTest = new searchTaoTest();

$results1 = array(1 => (object)array('id' => 1, 'objectType' => 'product', 'objectID' => 1));
$objectIdList1 = array(1 => 1);
$results2 = array(1 => (object)array('id' => 1, 'objectType' => 'product', 'objectID' => 5));
$objectIdList2 = array(5 => 1);
$results3 = array(1 => (object)array('id' => 1, 'objectType' => 'product', 'objectID' => 10));
$objectIdList3 = array(10 => 1);
$results4 = array(1 => (object)array('id' => 1, 'objectType' => 'program', 'objectID' => 1));
$objectIdList4 = array(1 => 1);
$results5 = array(1 => (object)array('id' => 1, 'objectType' => 'project', 'objectID' => 10));
$objectIdList5 = array(10 => 1);
$results6 = array(1 => (object)array('id' => 1, 'objectType' => 'execution', 'objectID' => 11));
$objectIdList6 = array(11 => 1);
$results7 = array(1 => (object)array('id' => 1, 'objectType' => 'doc', 'objectID' => 1));
$objectIdList7 = array(1 => 1);
$results8 = array(1 => (object)array('id' => 1, 'objectType' => 'todo', 'objectID' => 3));
$objectIdList8 = array(3 => 1);
$results9 = array(1 => (object)array('id' => 1, 'objectType' => 'testsuite', 'objectID' => 3));
$objectIdList9 = array(3 => 1);
$results10 = array(1 => (object)array('id' => 1, 'objectType' => 'unknown', 'objectID' => 1));
$objectIdList10 = array(1 => 1);

r(count($searchTest->checkObjectPrivTest('product', TABLE_PRODUCT, $results1, $objectIdList1, '1,2,3', '11,12,13'))) && p() && e('1');
r(count($searchTest->checkObjectPrivTest('product', TABLE_PRODUCT, $results2, $objectIdList2, '1,2,3', '11,12,13'))) && p() && e('0');
r(count($searchTest->checkObjectPrivTest('product', TABLE_PRODUCT, $results3, $objectIdList3, '1,2,3', '11,12,13'))) && p() && e('0');
r(count($searchTest->checkObjectPrivTest('program', TABLE_PROJECT, $results4, $objectIdList4, '1,2,3', '11,12,13'))) && p() && e('1');
r(count($searchTest->checkObjectPrivTest('project', TABLE_PROJECT, $results5, $objectIdList5, '1,2,3', '11,12,13'))) && p() && e('0');
r(count($searchTest->checkObjectPrivTest('execution', TABLE_PROJECT, $results6, $objectIdList6, '1,2,3', '11,12,13'))) && p() && e('1');
r(count($searchTest->checkObjectPrivTest('doc', TABLE_DOC, $results7, $objectIdList7, '1,2,3', '11,12,13'))) && p() && e('1');
r(count($searchTest->checkObjectPrivTest('todo', TABLE_TODO, $results8, $objectIdList8, '1,2,3', '11,12,13'))) && p() && e('0');
r(count($searchTest->checkObjectPrivTest('testsuite', TABLE_TESTSUITE, $results9, $objectIdList9, '1,2,3', '11,12,13'))) && p() && e('0');
r(count($searchTest->checkObjectPrivTest('unknown', TABLE_PRODUCT, $results10, $objectIdList10, '1,2,3', '11,12,13'))) && p() && e('1');