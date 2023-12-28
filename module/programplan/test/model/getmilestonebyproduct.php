#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('project')->config('project')->gen(6);
zdTable('projectproduct')->config('projectproduct')->gen(6);

su('admin');

/**

title=测试 programplanModel->getMilestoneByProduct();
cid=1
pid=1

*/

global $tester;

$tester->loadModel('programplan');
$result1 = $tester->programplan->getMilestoneByProduct(1, 1);
$result2 = $tester->programplan->getMilestoneByProduct(2, 4);

r(count($result1)) && p() && e('2'); // 测试获取产品1 项目1的里程碑个数
r(count($result2)) && p() && e('2'); // 测试获取产品2 项目4的里程碑个数

r($result1[2]) && p() && e('项目2'); // 测试结果1获得的里程碑对应的项目名称
r($result1[3]) && p() && e('项目3'); // 测试结果1获得的里程碑对应的项目名称
r($result2[5]) && p() && e('项目5'); // 测试结果2获得的里程碑对应的项目名称
r($result2[6]) && p() && e('项目6'); // 测试结果2获得的里程碑对应的项目名称
