#!/usr/bin/env php
<?php

/**

title=测试 buildZen::setMenuForView();
timeout=0
cid=15523

- 测试execution tab下版本1设置菜单 >> 期望session project设置为11
- 测试execution tab下版本2设置菜单 >> 期望session project设置为12
- 测试project tab下版本3设置菜单 >> 期望session project设置为13
- 测试project tab下版本9设置菜单 >> 期望session project设置为19
- 测试版本10时检查view变量设置完整性 >> 期望所有必需的view变量都已设置

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('build')->loadYaml('setmenuforview', false, 2)->gen(10);
zenData('project')->loadYaml('setmenuforview', false, 2)->gen(10);

su('admin');

global $tester;
$tester->app->tab = 'execution';

$buildTest = new buildZenTest();

$build1 = $tester->loadModel('build')->getById(1);
$build2 = $tester->loadModel('build')->getById(2);
$build3 = $tester->loadModel('build')->getById(3);
$build9 = $tester->loadModel('build')->getById(9);
$build10 = $tester->loadModel('build')->getById(10);

r($buildTest->setMenuForViewTest($build1)) && p('sessionProject') && e('11');
r($buildTest->setMenuForViewTest($build2)) && p('sessionProject') && e('12');
$tester->app->tab = 'project';
r($buildTest->setMenuForViewTest($build3)) && p('sessionProject') && e('13');
r($buildTest->setMenuForViewTest($build9)) && p('sessionProject') && e('19');
r($buildTest->setMenuForViewTest($build10)) && p('executionsSet,buildPairsSet,buildsSet,objectIDSet') && e('1,1,1,1');
