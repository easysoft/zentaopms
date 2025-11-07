#!/usr/bin/env php
<?php

/**

title=测试 buildZen::assignBugVarsForView();
timeout=0
cid=0

- 执行buildTest模块的assignBugVarsForViewTest方法，参数是$buildObj, 'bug', 'id_desc', '', $bugPager, $generatedBugPager 属性type @bug
- 执行buildTest模块的assignBugVarsForViewTest方法，参数是$buildObj, 'generatedBug', 'id_desc', '1', $bugPager, $generatedBugPager 属性type @generatedBug
- 执行buildTest模块的assignBugVarsForViewTest方法，参数是$buildObj, 'bug', 'id_desc', 'test', $bugPager, $generatedBugPager 属性param @test
- 执行buildTest模块的assignBugVarsForViewTest方法，参数是$buildObj, '', '', '', $bugPager, $generatedBugPager 属性type @~~
- 执行buildTest模块的assignBugVarsForViewTest方法，参数是$buildObj, 'bug', 'id_desc', '', $bugPager, $generatedBugPager 属性hasBugPager @1
- 执行buildTest模块的assignBugVarsForViewTest方法，参数是$buildObj, 'generatedBug', 'id_desc', '', $bugPager, $generatedBugPager 属性hasGeneratedPager @1
- 执行buildTest模块的assignBugVarsForViewTest方法，参数是$buildObj, 'bug', 'status_asc', '', $bugPager, $generatedBugPager 属性type @bug

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$bug = zenData('bug');
$bug->id->range('1-20');
$bug->product->range('1-3');
$bug->execution->range('1-5');
$bug->title->range('Bug 1-20')->prefix('Bug ');
$bug->status->range('active{10},resolved{5},closed{5}');
$bug->gen(20);

$build = zenData('build');
$build->id->range('1-10');
$build->product->range('1-3');
$build->project->range('1-3');
$build->execution->range('1-5');
$build->name->range('Build 1-10')->prefix('Build ');
$build->gen(10);

zenData('product')->gen(5);
zenData('project')->gen(5);
zenData('user')->gen(5);

global $tester;
$tester->app->loadClass('pager', true);
$tester->app->rawModule = 'build';
$tester->app->rawMethod = 'view';

su('admin');

$buildTest = new buildZenTest();

$buildObj = new stdclass();
$buildObj->id          = 1;
$buildObj->project     = 1;
$buildObj->product     = 1;
$buildObj->branch      = 0;
$buildObj->execution   = 1;
$buildObj->name        = 'Test Build';
$buildObj->allBugs     = '1,2,3,4,5';
$buildObj->builds      = '';

$bugPager          = new pager(0, 10, 1);
$generatedBugPager = new pager(0, 10, 1);

r($buildTest->assignBugVarsForViewTest($buildObj, 'bug', 'id_desc', '', $bugPager, $generatedBugPager)) && p('type') && e('bug');
r($buildTest->assignBugVarsForViewTest($buildObj, 'generatedBug', 'id_desc', '1', $bugPager, $generatedBugPager)) && p('type') && e('generatedBug');
r($buildTest->assignBugVarsForViewTest($buildObj, 'bug', 'id_desc', 'test', $bugPager, $generatedBugPager)) && p('param') && e('test');
r($buildTest->assignBugVarsForViewTest($buildObj, '', '', '', $bugPager, $generatedBugPager)) && p('type') && e('~~');
r($buildTest->assignBugVarsForViewTest($buildObj, 'bug', 'id_desc', '', $bugPager, $generatedBugPager)) && p('hasBugPager') && e('1');
r($buildTest->assignBugVarsForViewTest($buildObj, 'generatedBug', 'id_desc', '', $bugPager, $generatedBugPager)) && p('hasGeneratedPager') && e('1');
r($buildTest->assignBugVarsForViewTest($buildObj, 'bug', 'status_asc', '', $bugPager, $generatedBugPager)) && p('type') && e('bug');