#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('user')->gen(5);
su('admin');

$project = zenData('project')->loadYaml('execution');
$project->lifetime->range('ops,dev');
$project->gen(10);

/**

title=测试executionModel->removeMenu();
timeout=0
cid=16360

- 测试移除燃尽图的导航 @1
- 测试移除版本的导航 @1
- 测试移除qa的导航 @1
- 测试移除需求的导航 @1
- 测试移除燃尽图的导航 @0
- 测试移除版本的导航 @0
- 测试移除qa的导航 @0
- 测试移除需求的导航 @0

*/

$executionIdList = array(11, 60);

global $lang;
$executionMenu = clone $lang->execution->menu;
$executionTester = new executionModelTest();

$lang->execution->menu = clone $executionMenu;
r(empty($executionTester->removeMenuTest($executionIdList[0])->burn))  && p('') && e('1'); // 测试移除燃尽图的导航
$lang->execution->menu = clone $executionMenu;
r(empty($executionTester->removeMenuTest($executionIdList[0])->build)) && p('') && e('1'); // 测试移除版本的导航
$lang->execution->menu = clone $executionMenu;
r(empty($executionTester->removeMenuTest($executionIdList[0])->qa))    && p('') && e('1'); // 测试移除qa的导航
$lang->execution->menu = clone $executionMenu;
r(empty($executionTester->removeMenuTest($executionIdList[0])->story)) && p('') && e('1'); // 测试移除需求的导航
$lang->execution->menu = clone $executionMenu;
r(empty($executionTester->removeMenuTest($executionIdList[1])->burn))  && p('') && e('0'); // 测试移除燃尽图的导航
$lang->execution->menu = clone $executionMenu;
r(empty($executionTester->removeMenuTest($executionIdList[1])->build)) && p('') && e('0'); // 测试移除版本的导航
$lang->execution->menu = clone $executionMenu;
r(empty($executionTester->removeMenuTest($executionIdList[1])->qa))    && p('') && e('0'); // 测试移除qa的导航
$lang->execution->menu = clone $executionMenu;
r(empty($executionTester->removeMenuTest($executionIdList[1])->story)) && p('') && e('0'); // 测试移除需求的导航
