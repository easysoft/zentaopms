#!/usr/bin/env php
<?php

/**

title=测试 companyZen::buildDyanmicSearchForm();
timeout=0
cid=0

- 执行companyTest模块的buildDyanmicSearchFormTest方法，参数是$products, $projects, $executions, 1, 1  @admin
- 执行companyTest模块的buildDyanmicSearchFormTest方法，参数是array  @all
- 执行companyTest模块的buildDyanmicSearchFormTest方法，参数是$products, $projects, $executions, 1, 2  @admin
- 执行companyTest模块的buildDyanmicSearchFormTest方法，参数是$products, $projects, $executions, 999, 3  @all
- 执行companyTest模块的buildDyanmicSearchFormTest方法，参数是$products, $projects, $executions, 0, 5  @all

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/company.unittest.class.php';

$userTable = zenData('user');
$userTable->id->range('1-10');
$userTable->account->range('admin,user1,user2,user3,user4,test1,test2,test3,test4,test5');
$userTable->password->range('123456{10}');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4,测试1,测试2,测试3,测试4,测试5');
$userTable->role->range('admin{1},dev{5},qa{2},pm{2}');
$userTable->dept->range('1-5');
$userTable->gen(10);

$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5');
$productTable->code->range('prod1,prod2,prod3,prod4,prod5');
$productTable->status->range('normal{5}');
$productTable->gen(5);

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->name->range('项目1,项目2,项目3,项目4,项目5');
$projectTable->code->range('proj1,proj2,proj3,proj4,proj5');
$projectTable->type->range('project{5}');
$projectTable->status->range('wait{2},doing{2},done{1}');
$projectTable->gen(5);

$executionTable = zenData('project');
$executionTable->id->range('11-15');
$executionTable->name->range('执行1,执行2,执行3,执行4,执行5');
$executionTable->code->range('exec1,exec2,exec3,exec4,exec5');
$executionTable->type->range('execution{5}');
$executionTable->parent->range('1-5');
$executionTable->project->range('1-5');
$executionTable->status->range('wait{2},doing{2},done{1}');
$executionTable->gen(5);

su('admin');

$companyTest = new companyTest();

// 步骤1：正常参数测试
$products = array(1 => '产品1', 2 => '产品2');
$projects = array(1 => '项目1', 2 => '项目2');
$executions = array(1 => '执行1', 2 => '执行2');
r($companyTest->buildDyanmicSearchFormTest($products, $projects, $executions, 1, 1)) && p() && e('admin');

// 步骤2：空数组参数测试
r($companyTest->buildDyanmicSearchFormTest(array(), array(), array(), 0, 0)) && p() && e('all');

// 步骤3：传入特定用户ID
r($companyTest->buildDyanmicSearchFormTest($products, $projects, $executions, 1, 2)) && p() && e('admin');

// 步骤4：传入无效用户ID
r($companyTest->buildDyanmicSearchFormTest($products, $projects, $executions, 999, 3)) && p() && e('all');

// 步骤5：测试不同查询ID
r($companyTest->buildDyanmicSearchFormTest($products, $projects, $executions, 0, 5)) && p() && e('all');