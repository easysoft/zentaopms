#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';

/**

title=测试 testcaseModel->createStepsFromBug();
cid=1
pid=1


*/

$testcase = new testcaseTest();

$steps1   = "<p>[步骤]</p><br/>一个步骤\n<p>[结果]</p><br/>一个结果\n<br/><p>[期望]</p><br/>一个期望\n<br/>";
$steps2   = "<p>[步骤]</p><br/>1. 步骤1\n2. 步骤2<br/><p>[结果]</p><br/>1. 结果1\n2. 结果2\n<br/><p>[期望]</p><br/>期望\n<br/>";
$noStep   = "<p>[结果]</p><br/>一个结果\n<p>[期望]</p><br/>一个期望\n";
$noResult = "<p>[步骤]</p><br/>一个步骤\n<p>[期望]</p><br/>一个期望\n";
$noExpect = "<p>[步骤]</p><br/>一个步骤\n<p>[结果]</p><br/>一个结果\n";

r($testcase->createStepsFromBugTest($steps1))   && p() && e('step:一个步骤 expect:一个期望 type:item.'); // 测试从 bug 中创建步骤 steps1
r($testcase->createStepsFromBugTest($steps2))   && p() && e('step:1. 步骤1 expect: type:item.   step:2. 步骤2 expect:期望 type:item.'); // 测试从 bug 中创建步骤 steps2
r($testcase->createStepsFromBugTest($noStep))   && p() && e('step:[结果]一个结果 [期望]一个期望 expect:'); // 测试从 bug 中创建步骤 没有步骤
r($testcase->createStepsFromBugTest($noResult)) && p() && e('step:[步骤]一个步骤 [期望]一个期望 expect:'); // 测试从 bug 中创建步骤 没有结果
r($testcase->createStepsFromBugTest($noExpect)) && p() && e('step:[步骤]一个步骤 [结果]一个结果 expect:'); // 测试从 bug 中创建步骤 没有期望
