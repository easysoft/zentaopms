#!/usr/bin/env php
<?php

/**

title=- 执行buildTest模块的setMenuForViewTest方法，参数是$build1 属性title @BUILD
timeout=0
cid=1

- 执行buildTest模块的setMenuForViewTest方法，参数是$build1 属性title @BUILD #1 Build001_release - 执行101
- 执行buildTest模块的setMenuForViewTest方法，参数是$build2 属性objectType @project
- 执行buildTest模块的setMenuForViewTest方法，参数是$build3 属性objectID @0
- 执行buildTest模块的setMenuForViewTest方法，参数是$build4 属性sessionProject @14
- 执行buildTest模块的setMenuForViewTest方法，参数是$build5 属性executionCount @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

su('admin');

$buildTest = new buildTest();

$build1 = new stdclass();
$build1->id = 1;
$build1->name = 'Build001_release';
$build1->execution = 101;
$build1->project = 11;

$build2 = new stdclass();
$build2->id = 2;
$build2->name = 'Build002_alpha';
$build2->execution = 102;
$build2->project = 12;

$build3 = new stdclass();
$build3->id = 3;
$build3->name = 'Build003_beta';
$build3->execution = 0;
$build3->project = 13;

$build4 = new stdclass();
$build4->id = 4;
$build4->name = 'Build004_stable';
$build4->execution = 104;
$build4->project = 14;

$build5 = new stdclass();
$build5->id = 5;
$build5->name = 'Build005_hotfix';
$build5->execution = 105;
$build5->project = 15;

global $tester;

$tester->app->tab = 'execution';
r($buildTest->setMenuForViewTest($build1)) && p('title') && e('BUILD #1 Build001_release - 执行101');

$tester->app->tab = 'project';
r($buildTest->setMenuForViewTest($build2)) && p('objectType') && e('project');

$tester->app->tab = 'execution';
r($buildTest->setMenuForViewTest($build3)) && p('objectID') && e('0');

r($buildTest->setMenuForViewTest($build4)) && p('sessionProject') && e('14');

r($buildTest->setMenuForViewTest($build5)) && p('executionCount') && e('3');