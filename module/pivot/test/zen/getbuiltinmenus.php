#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::getBuiltinMenus();
timeout=0
cid=0

- 执行pivotTest模块的getBuiltinMenusTest方法，参数是1, $productGroup  @0
- 执行pivotTest模块的getBuiltinMenusTest方法，参数是1, $projectGroup  @0
- 执行pivotTest模块的getBuiltinMenusTest方法，参数是1, $testGroup  @0
- 执行pivotTest模块的getBuiltinMenusTest方法，参数是1, $staffGroup  @0
- 执行pivotTest模块的getBuiltinMenusTest方法，参数是1, $emptyGroup  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivotzen.unittest.class.php';

zenData('module')->loadYaml('getbuiltinmenus', false, 2)->gen(10);
zenData('user')->gen(1);
zenData('group')->loadYaml('getbuiltinmenus', false, 2)->gen(1);
zenData('usergroup')->loadYaml('getbuiltinmenus', false, 2)->gen(1);
zenData('grouppriv')->loadYaml('getbuiltinmenus', false, 2)->gen(5);

su('admin');

global $app;
$app->loadLang('pivot');

$pivotTest = new pivotZenTest();

$productGroup = new stdclass();
$productGroup->id = 1;
$productGroup->collector = 'product';
$productGroup->grade = 1;

$projectGroup = new stdclass();
$projectGroup->id = 2;
$projectGroup->collector = 'project';
$projectGroup->grade = 1;

$testGroup = new stdclass();
$testGroup->id = 3;
$testGroup->collector = 'test';
$testGroup->grade = 1;

$staffGroup = new stdclass();
$staffGroup->id = 4;
$staffGroup->collector = 'staff';
$staffGroup->grade = 1;

$emptyGroup = new stdclass();
$emptyGroup->id = 5;
$emptyGroup->collector = 'empty';
$emptyGroup->grade = 1;

r(count($pivotTest->getBuiltinMenusTest(1, $productGroup))) && p() && e('0');
r(count($pivotTest->getBuiltinMenusTest(1, $projectGroup))) && p() && e('0');
r(count($pivotTest->getBuiltinMenusTest(1, $testGroup))) && p() && e('0');
r(count($pivotTest->getBuiltinMenusTest(1, $staffGroup))) && p() && e('0');
r(count($pivotTest->getBuiltinMenusTest(1, $emptyGroup))) && p() && e('0');