#!/usr/bin/env php
<?php

/**

title=测试 buildModel->setBuildDateGroup();
timeout=0
cid=1

- 获取版本1-10的数据第2021-01-01条的1属性 @版本1
- 获取版本1-10且不关联分支的数据第2021-01-01条的1属性 @版本1
- 获取版本1-10且关联分支1的数据 @0
- 获取版本1-10且未完成的数据第2030-01-01条的5属性 @版本5
- 获取版本1-10且未终止的数据第2021-01-01条的1属性 @版本1
- 获取版本1-10且关联执行的数据第2021-01-01条的1属性 @版本1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

zenData('build')->loadYaml('build')->gen(10);
zenData('project')->loadYaml('execution')->gen(30);
zenData('branch')->loadYaml('branch')->gen(5);
zenData('user')->gen(5);
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
