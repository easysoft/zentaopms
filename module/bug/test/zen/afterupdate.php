#!/usr/bin/env php
<?php

/**

title=测试 bugZen::afterUpdate();
timeout=0
cid=0

- 执行bugTest模块的afterUpdateTest方法，参数是$bug, $oldBug  @1
- 执行bugTest模块的afterUpdateTest方法，参数是$bug, $oldBug  @1
- 执行bugTest模块的afterUpdateTest方法，参数是$bug, $oldBug  @1
- 执行bugTest模块的afterUpdateTest方法，参数是$bug, $oldBug  @1
- 执行bugTest模块的afterUpdateTest方法，参数是$bug, $oldBug  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

zenData('bug')->gen(5);
zenData('build')->gen(3);
zenData('productplan')->gen(3);
zenData('action')->gen(0);

su('admin');

$bugTest = new bugTest();

// 测试正常情况，无任何变化
$bug = (object)array('id' => 1, 'resolvedBuild' => '1', 'plan' => 1, 'status' => 'active', 'execution' => 0, 'resolvedBy' => '', 'feedback' => 0);
$oldBug = (object)array('resolvedBuild' => '1', 'plan' => 1, 'status' => 'active', 'feedback' => 0);
r($bugTest->afterUpdateTest($bug, $oldBug)) && p() && e('1');

// 测试解决版本变化
$bug = (object)array('id' => 2, 'resolvedBuild' => '2', 'plan' => 1, 'status' => 'resolved', 'execution' => 0, 'resolvedBy' => 'admin', 'feedback' => 0);
$oldBug = (object)array('resolvedBuild' => '1', 'plan' => 1, 'status' => 'active', 'feedback' => 0);
r($bugTest->afterUpdateTest($bug, $oldBug)) && p() && e('1');

// 测试计划变化
$bug = (object)array('id' => 3, 'resolvedBuild' => '1', 'plan' => 2, 'status' => 'active', 'execution' => 0, 'resolvedBy' => '', 'feedback' => 0);
$oldBug = (object)array('resolvedBuild' => '1', 'plan' => 1, 'status' => 'active', 'feedback' => 0);
r($bugTest->afterUpdateTest($bug, $oldBug)) && p() && e('1');

// 测试状态变化有执行
$bug = (object)array('id' => 4, 'resolvedBuild' => '1', 'plan' => 1, 'status' => 'resolved', 'execution' => 101, 'resolvedBy' => 'admin', 'feedback' => 0);
$oldBug = (object)array('resolvedBuild' => '1', 'plan' => 1, 'status' => 'active', 'feedback' => 0);
r($bugTest->afterUpdateTest($bug, $oldBug)) && p() && e('1');

// 测试有反馈情况（仅企业版/专业版支持）
$bug = (object)array('id' => 5, 'resolvedBuild' => '', 'plan' => 0, 'status' => 'closed', 'execution' => 0, 'resolvedBy' => '', 'feedback' => 0);
$oldBug = (object)array('resolvedBuild' => '', 'plan' => 0, 'status' => 'resolved', 'feedback' => 1);
r($bugTest->afterUpdateTest($bug, $oldBug)) && p() && e('1');