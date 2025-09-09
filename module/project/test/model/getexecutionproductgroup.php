#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('projectproduct')->loadYaml('projectproduct_getexecutionproductgroup', false, 2)->gen(15);

/**

title=测试 projectModel->getExecutionProductGroup();
timeout=0
cid=0

- 测试获取多个执行ID的产品分组，检查项目11的第一个产品 @1
- 测试获取多个执行ID的产品分组，检查项目12的第一个产品 @2
- 测试获取单个执行ID的产品分组，检查结果的项目数量 @1
- 测试传入空数组的情况，检查结果数量 @0
- 测试传入不存在执行ID的情况，检查结果数量 @0

*/

global $tester;
$tester->loadModel('project');

$result1 = $tester->project->getExecutionProductGroup(array(11, 12, 13));
$result2 = $tester->project->getExecutionProductGroup(array(11));
$result3 = $tester->project->getExecutionProductGroup(array());
$result4 = $tester->project->getExecutionProductGroup(array(999, 1000));
$result5 = $tester->project->getExecutionProductGroup(array(11, 999, 12));

r($result1[11][0]) && p('') && e('1'); // 测试获取多个执行ID的产品分组，检查项目11的第一个产品
r($result1[12][0]) && p('') && e('2'); // 测试获取多个执行ID的产品分组，检查项目12的第一个产品
r(count($result2))  && p('') && e('1'); // 测试获取单个执行ID的产品分组，检查结果的项目数量
r(count($result3))  && p('') && e('0'); // 测试传入空数组的情况，检查结果数量
r(count($result4))  && p('') && e('0'); // 测试传入不存在执行ID的情况，检查结果数量