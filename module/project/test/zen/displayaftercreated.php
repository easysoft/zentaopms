#!/usr/bin/env php
<?php

/**

title=测试 projectZen::displayAfterCreated();
timeout=0
cid=0

- 执行projectzenTest模块的displayAfterCreatedTest方法，参数是1  @valid project id
- 执行projectzenTest模块的displayAfterCreatedTest方法，参数是999  @non-existent project id
- 执行projectzenTest模块的displayAfterCreatedTest方法  @zero project id
- 执行projectzenTest模块的displayAfterCreatedTest方法，参数是-1  @negative project id
- 执行projectzenTest模块的displayAfterCreatedTest方法，参数是'invalid'  @projectID parameter must be int

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

$table = zenData('project');
$table->id->range('1-10');
$table->name->range('项目{1-10}');
$table->status->range('wait,doing,done');
$table->multiple->range('0,1');
$table->hasProduct->range('0,1');
$table->gen(10);

$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品{1-5}');
$productTable->status->range('normal');
$productTable->gen(5);

$executionTable = zenData('execution');
$executionTable->id->range('1-5');
$executionTable->project->range('1-5');
$executionTable->name->range('执行{1-5}');
$executionTable->status->range('wait,doing,done');
$executionTable->gen(5);

su('admin');

$projectzenTest = new projectzenTest();

r($projectzenTest->displayAfterCreatedTest(1)) && p() && e('valid project id');
r($projectzenTest->displayAfterCreatedTest(999)) && p() && e('non-existent project id');
r($projectzenTest->displayAfterCreatedTest(0)) && p() && e('zero project id');
r($projectzenTest->displayAfterCreatedTest(-1)) && p() && e('negative project id');
r($projectzenTest->displayAfterCreatedTest('invalid')) && p() && e('projectID parameter must be int');