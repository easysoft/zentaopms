#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('branch')->loadYaml('branch')->gen(30);
zenData('user')->gen(5);
su('admin');

/**

title=测试 branchModel->getPairsByIdList();
timeout=0
cid=15328

- 测试传入空的分支ID列表 @0
- 测试传入正常的分支ID列表属性1 @分支1
- 测试传入不存在的分支ID列表 @0
- 测试传入空的分支ID数量 @0
- 测试传入正常的分支ID数量 @20
- 测试传入不存在的分支ID数量 @0

*/

$branchIdList[0] = array();
$branchIdList[1] = range(1, 20);
$branchIdList[2] = range(100, 120);

global $tester;
$tester->loadModel('branch');
r($tester->branch->getPairsByIdList($branchIdList[0]))        && p()    && e('0');     // 测试传入空的分支ID列表
r($tester->branch->getPairsByIdList($branchIdList[1]))        && p('1') && e('分支1'); // 测试传入正常的分支ID列表
r($tester->branch->getPairsByIdList($branchIdList[2]))        && p()    && e('0');     // 测试传入不存在的分支ID列表
r(count($tester->branch->getPairsByIdList($branchIdList[0]))) && p()    && e('0');     // 测试传入空的分支ID数量
r(count($tester->branch->getPairsByIdList($branchIdList[1]))) && p()    && e('20');    // 测试传入正常的分支ID数量
r(count($tester->branch->getPairsByIdList($branchIdList[2]))) && p()    && e('0');     // 测试传入不存在的分支ID数量
