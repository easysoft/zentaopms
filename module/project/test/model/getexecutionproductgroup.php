#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1{3},2{2},3{2},4,5{2}');
$projectproduct->product->range('1,2,3,4,5,6,7,8,9,10');
$projectproduct->branch->range('0');
$projectproduct->gen(10);

/**

title=测试 projectModel::getExecutionProductGroup();
timeout=0
cid=17826

- 传入多个执行ID,获取执行1的产品数量 @3
- 传入多个执行ID,获取执行2的产品数量 @2
- 传入多个执行ID,获取执行3的产品数量 @2
- 传入多个执行ID,获取执行1的第一个产品ID @1
- 传入单个执行ID,检查执行1的产品数量 @3
- 传入不存在的执行ID,返回空数组 @0
- 传入空数组,返回空数组 @0
- 传入包含有效和无效执行ID的混合数组,检查返回的执行数量 @2

*/

global $tester;
$tester->loadModel('project');

$result1 = $tester->project->getExecutionProductGroup(array(1, 2, 3));
$result2 = $tester->project->getExecutionProductGroup(array(1));
$result3 = $tester->project->getExecutionProductGroup(array(999));
$result4 = $tester->project->getExecutionProductGroup(array());
$result5 = $tester->project->getExecutionProductGroup(array(1, 999, 2));

r(count($result1[1])) && p('') && e('3'); // 传入多个执行ID,获取执行1的产品数量
r(count($result1[2])) && p('') && e('2'); // 传入多个执行ID,获取执行2的产品数量
r(count($result1[3])) && p('') && e('2'); // 传入多个执行ID,获取执行3的产品数量
r(key($result1[1])) && p('') && e('1'); // 传入多个执行ID,获取执行1的第一个产品ID
r(count($result2[1])) && p('') && e('3'); // 传入单个执行ID,检查执行1的产品数量
r(count($result3)) && p('') && e('0'); // 传入不存在的执行ID,返回空数组
r(count($result4)) && p('') && e('0'); // 传入空数组,返回空数组
r(count($result5)) && p('') && e('2'); // 传入包含有效和无效执行ID的混合数组,检查返回的执行数量