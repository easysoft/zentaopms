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

r($testcase->createStepsFromBugTest($steps1))   && p('0:name,desc,step,expect,type')        && e('1,一个步骤,一个步骤,一个期望,item');                              // 测试从 bug 中创建步骤 steps1
r($testcase->createStepsFromBugTest($steps2))   && p('0:name,desc,expect;1:name,step,type') && e('1,1. 步骤1,` `;1,2. 步骤2,item');                                 // 测试从 bug 中创建步骤 steps2
r($testcase->createStepsFromBugTest($noStep))   && p('0:name,desc,step,expect,type') && e('1,[结果]一个结果 [期望]一个期望,[结果]一个结果 [期望]一个期望,` `,` `'); // 测试从 bug 中创建步骤 没有步骤
r($testcase->createStepsFromBugTest($noResult)) && p('0:name,desc,step,expect,type') && e('1,[步骤]一个步骤 [期望]一个期望,[步骤]一个步骤 [期望]一个期望,` `,` `'); // 测试从 bug 中创建步骤 没有结果
r($testcase->createStepsFromBugTest($noExpect)) && p('0:name,desc,step,expect,type') && e('1,[步骤]一个步骤 [结果]一个结果,[步骤]一个步骤 [结果]一个结果,` `,` `'); // 测试从 bug 中创建步骤 没有期望
