#!/usr/bin/env php
<?php

/**

title=测试 buildModel::joinChildBuilds();
timeout=0
cid=15502

- 执行$result1
 - 属性allBugs @1
- 执行$result2
 - 属性allBugs @1
- 执行$result3
 - 属性allBugs @25
- 执行$result4
 - 属性allBugs @10
- 执行$result5
 - 属性allStories @25

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zendata('build')->loadYaml('build_joinchildbuilds', false, 2)->gen(10);

su('admin');

$buildTest = new buildModelTest();

// 测试步骤1：无子版本的版本对象
$testBuild1 = new stdClass();
$testBuild1->id = 1;
$testBuild1->builds = '';
$testBuild1->bugs = '1,2,3';
$testBuild1->stories = '4,5,6';
$result1 = $buildTest->joinChildBuildsTest($testBuild1);
r($result1) && p('allBugs') && e('1,2,3');

// 测试步骤2：有子版本的版本对象
$testBuild2 = new stdClass();
$testBuild2->id = 2;
$testBuild2->builds = '7,8';
$testBuild2->bugs = '1,2';
$testBuild2->stories = '4,5';
$result2 = $buildTest->joinChildBuildsTest($testBuild2);
r($result2) && p('allBugs') && e('1,2,19,20,22,23');

// 测试步骤3：空bugs和stories的版本对象
$testBuild3 = new stdClass();
$testBuild3->id = 3;
$testBuild3->builds = '9';
$testBuild3->bugs = '';
$testBuild3->stories = '';
$result3 = $buildTest->joinChildBuildsTest($testBuild3);
r($result3) && p('allBugs') && e('25,26');

// 测试步骤4：builds字段为空的版本对象
$testBuild4 = new stdClass();
$testBuild4->id = 6;
$testBuild4->builds = '';
$testBuild4->bugs = '10,11,12';
$testBuild4->stories = '20,21,22';
$result4 = $buildTest->joinChildBuildsTest($testBuild4);
r($result4) && p('allBugs') && e('10,11,12');

// 测试步骤5：复合测试场景验证方法处理的正确性
$testBuild5 = new stdClass();
$testBuild5->id = 5;
$testBuild5->builds = '1,2';
$testBuild5->bugs = '15,16';
$testBuild5->stories = '25,26';
$result5 = $buildTest->joinChildBuildsTest($testBuild5);
r($result5) && p('allStories') && e('25,26,2,4,6,8');