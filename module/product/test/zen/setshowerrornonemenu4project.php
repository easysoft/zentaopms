#!/usr/bin/env php
<?php

/**

title=测试 productZen::setShowErrorNoneMenu4Project();
timeout=0
cid=0

- 执行productTest模块的setShowErrorNoneMenu4ProjectTest方法，参数是'bug', 1 属性bugSubModuleSet @1
- 执行productTest模块的setShowErrorNoneMenu4ProjectTest方法，参数是'testcase', 2 属性testcaseSubModuleSet @1
- 执行productTest模块的setShowErrorNoneMenu4ProjectTest方法，参数是'testtask', 3 属性testtaskSubModuleSet @1
- 执行productTest模块的setShowErrorNoneMenu4ProjectTest方法，参数是'testreport', 4 属性testreportSubModuleSet @1
- 执行productTest模块的setShowErrorNoneMenu4ProjectTest方法，参数是'projectrelease', 5 属性projectreleaseSubModuleSet @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

$table = zenData('project');
$table->id->range('1-10');
$table->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$table->model->range('scrum{3},waterfall{3},agileplus{2},kanban{2}');
$table->status->range('wait{2},doing{5},suspended{1},closed{2}');
$table->type->range('project{8},program{2}');
$table->gen(10);

su('admin');

$productTest = new productTest();

r($productTest->setShowErrorNoneMenu4ProjectTest('bug', 1)) && p('bugSubModuleSet') && e('1');
r($productTest->setShowErrorNoneMenu4ProjectTest('testcase', 2)) && p('testcaseSubModuleSet') && e('1');
r($productTest->setShowErrorNoneMenu4ProjectTest('testtask', 3)) && p('testtaskSubModuleSet') && e('1');
r($productTest->setShowErrorNoneMenu4ProjectTest('testreport', 4)) && p('testreportSubModuleSet') && e('1');
r($productTest->setShowErrorNoneMenu4ProjectTest('projectrelease', 5)) && p('projectreleaseSubModuleSet') && e('1');