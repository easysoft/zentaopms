#!/usr/bin/env php
<?php

/**

title=测试 buildModel->setBuildDateGroup();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/build.class.php';

zdTable('build')->config('build')->gen(10);
zdTable('project')->config('execution')->gen(30);
zdTable('branch')->config('branch')->gen(5);
zdTable('user')->gen(5);
su('admin');

$branchIdList = array('all', '0', '1');
$paramsList   = array('', 'nodone', 'noterminate', 'withexecution', 'withbranch');

$buildTester = new buildTest();
r($buildTester->setBuildDateGroupTest($branchIdList[0], $paramsList[0])) && p('2021-01-01:1') && e('版本1'); // 获取版本1-10的数据
r($buildTester->setBuildDateGroupTest($branchIdList[1], $paramsList[0])) && p('2021-01-01:1') && e('版本1'); // 获取版本1-10且不关联分支的数据
r($buildTester->setBuildDateGroupTest($branchIdList[2], $paramsList[0])) && p()               && e('0');     // 获取版本1-10且关联分支1的数据
r($buildTester->setBuildDateGroupTest($branchIdList[0], $paramsList[1])) && p('2030-01-01:5') && e('版本5'); // 获取版本1-10且未完成的数据
r($buildTester->setBuildDateGroupTest($branchIdList[0], $paramsList[2])) && p('2021-01-01:1') && e('版本1'); // 获取版本1-10且未终止的数据
r($buildTester->setBuildDateGroupTest($branchIdList[0], $paramsList[3])) && p('2021-01-01:1') && e('版本1'); // 获取版本1-10且关联执行的数据
r($buildTester->setBuildDateGroupTest($branchIdList[0], $paramsList[4])) && p('2021-01-01:1') && e('版本1'); // 获取版本1-10且关联分支的数据
