#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getModulesForCreate();
timeout=0
cid=0

- 执行bugTest模块的getModulesForCreateTest方法，参数是$bug1 属性moduleID @3
- 执行bugTest模块的getModulesForCreateTest方法，参数是$bug2 属性moduleID @6
- 执行bugTest模块的getModulesForCreateTest方法，参数是$bug3 属性moduleID @9
- 执行bugTest模块的getModulesForCreateTest方法，参数是$bug4 属性moduleID @~~
- 执行bugTest模块的getModulesForCreateTest方法，参数是$bug5 属性moduleID @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

zenData('product')->loadYaml('product', false, 2)->gen(5);
zenData('module')->loadYaml('module_bug', false, 2)->gen(10);
zenData('user')->loadYaml('user_getmodulesforcreate', false, 2)->gen(5);

su('admin');

$bugTest = new bugTest();

$bug1 = new stdclass();
$bug1->productID = 1;
$bug1->branch = '';
$bug1->moduleID = 3;
$bug1->branches = array('' => '');

$bug2 = new stdclass();
$bug2->productID = 1;
$bug2->branch = '';
$bug2->moduleID = 6;
$bug2->branches = array('' => '');

$bug3 = new stdclass();
$bug3->productID = 1;
$bug3->branch = '';
$bug3->moduleID = 9;
$bug3->branches = array('' => '');

$bug4 = new stdclass();
$bug4->productID = 1;
$bug4->branch = '';
$bug4->moduleID = 999;
$bug4->branches = array('' => '');

$bug5 = new stdclass();
$bug5->productID = 999;
$bug5->branch = '';
$bug5->moduleID = 1;
$bug5->branches = array('' => '');

r($bugTest->getModulesForCreateTest($bug1)) && p('moduleID') && e('3');
r($bugTest->getModulesForCreateTest($bug2)) && p('moduleID') && e('6');
r($bugTest->getModulesForCreateTest($bug3)) && p('moduleID') && e('9');
r($bugTest->getModulesForCreateTest($bug4)) && p('moduleID') && e('~~');
r($bugTest->getModulesForCreateTest($bug5)) && p('moduleID') && e('~~');