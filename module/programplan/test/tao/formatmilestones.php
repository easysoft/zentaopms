#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('project')->config('project')->gen(6);
zdTable('projectproduct')->config('projectproduct')->gen(6);
su('admin');

/**

title=测试 programplanTao->formatMilestones();
cid=1
pid=1

*/

global $tester;

$tester->loadModel('programplan')->programplanTao;

/** 测试阶段id为2,3,5,6的里程碑数据  */
$data1 = array(2 => ',2,12,', 3 => ',3,13,');
$data2 = array(5 => ',5,15,', 6 => ',6,16,');

$result1 = $tester->programplan->formatMilestones($data1, 1);
$result2 = $tester->programplan->formatMilestones($data2, 4);

r(count($result1)) && p() && e('2'); // 测试获取产品1 项目1的里程碑个数
r(count($result2)) && p() && e('2'); // 测试获取产品2 项目4的里程碑个数

r($result1[2]) && p() && e('项目2'); // 测试结果1获得的里程碑对应的项目名称
r($result1[3]) && p() && e('项目3'); // 测试结果1获得的里程碑对应的项目名称
r($result2[5]) && p() && e('项目5'); // 测试结果2获得的里程碑对应的项目名称
r($result2[6]) && p() && e('项目6'); // 测试结果2获得的里程碑对应的项目名称
