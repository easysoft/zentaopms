#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::setBrowseMenu();
timeout=0
cid=0

- 步骤1：在qa应用中设置菜单,传入产品ID=1和分支=main,返回产品ID @1
- 步骤2：在project应用中设置菜单,传入产品ID=2和项目ID=1,返回产品ID @2
- 步骤3：传入产品ID=1和空分支参数,返回产品ID @1
- 步骤4：在project应用中传入产品ID=1和分支=branch1,返回产品ID @1
- 步骤5：在qa应用中传入产品ID=3和分支=all,返回产品I @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

zenData('product')->loadYaml('product', false, 2)->gen(10);

$projectTable = zenData('project');
$projectTable->id->range('1-20');
$projectTable->name->range('项目1,项目2,项目3,项目4{2},项目5{15}');
$projectTable->type->range('project{10},sprint{10}');
$projectTable->hasProduct->range('1{15},0{5}');
$projectTable->status->range('doing{15},closed{5}');
$projectTable->gen(20);

zenData('projectproduct')->loadYaml('projectproduct', false, 2)->gen(30);

su('admin');

$testcaseZenTest = new testcaseZenTest();

r($testcaseZenTest->setBrowseMenuTest(1, 'main', 0)) && p('0') && e('1'); // 步骤1：在qa应用中设置菜单,传入产品ID=1和分支=main,返回产品ID
r($testcaseZenTest->setBrowseMenuTest(2, 'all', 1)) && p('0') && e('2'); // 步骤2：在project应用中设置菜单,传入产品ID=2和项目ID=1,返回产品ID
r($testcaseZenTest->setBrowseMenuTest(1, '0', 0)) && p('0') && e('1'); // 步骤3：传入产品ID=1和空分支参数,返回产品ID
r($testcaseZenTest->setBrowseMenuTest(1, 'branch1', 2)) && p('0') && e('1'); // 步骤4：在project应用中传入产品ID=1和分支=branch1,返回产品ID
r($testcaseZenTest->setBrowseMenuTest(3, 'all', 0)) && p('0') && e('3'); // 步骤5：在qa应用中传入产品ID=3和分支=all,返回产品I