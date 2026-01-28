#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('bug')->gen(100);
zenData('userquery')->loadYaml('userquery')->gen(1);
zenData('product')->gen(100);

/**

title=bugModel->getUserBugs();
timeout=0
cid=15400

- 测试获取用户admin 被指派的 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 的bug数量 @30
- 测试获取用户admin 关闭的 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 的bug数量 @30
- 测试获取用户admin 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 的bug数量 @0
- 测试获取用户admin 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 没有queryID 没有query值 的bug数量 @90
- 测试获取用户admin 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 有queryID 没有query值 的bug数量 @18
- 测试获取用户admin 被指派的 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 没有queryID 有query值 的bug数量 @18
- 测试获取用户admin 被指派的 不限制数量的 executionID为0的 queryID 为1的 调用方法为work 有queryID 有query值的 的bug数量 @18
- 测试获取用户admin 被指派的 限制数量10的 executionID为0的 queryID 为0的 调用方法为work 的bug数量 @10
- 测试获取用户admin 被指派的 限制数量10的 executionID为0的 queryID 为1的 调用方法为work 有queryID query值的 的bug数量 @10
- 测试获取用户admin 被指派的 不限制数量的 executionID为101的 queryID 为0的 调用方法为work 的bug数量 @3
- 测试获取用户admin 被指派的 不限制数量的 executionID为101的 queryID 为1的 调用方法为work 有queryID query值的 的bug数量 @1
- 测试获取用户admin 被指派的 不限制数量的 executionID为0的 queryID 为0的 调用方法为contributeBug 的bug数量 @30
- 测试获取用户admin 被指派的 不限制数量的 executionID为0的 queryID 为0的 调用方法为contributeBug 有queryID query值的 的bug数量 @18
- 测试获取用户admin 被指派的 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 的bug数量 @20
- 测试获取用户admin 关闭的 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 的bug数量 @0
- 测试获取用户admin 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 的bug数量 @0
- 测试获取用户admin 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 没有queryID 没有query值 的bug数量 @0
- 测试获取用户admin 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 有queryID 没有query值 的bug数量 @0
- 测试获取用户admin 被指派的 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 没有queryID 有query值 的bug数量 @0
- 测试获取用户admin 被指派的 不限制数量的 executionID为0的 queryID 为1的 调用方法为work 有queryID 有query值的 的bug数量 @0
- 测试获取用户admin 被指派的 限制数量10的 executionID为0的 queryID 为0的 调用方法为work 的bug数量 @10
- 测试获取用户admin 被指派的 限制数量10的 executionID为0的 queryID 为1的 调用方法为work 有queryID query值的 的bug数量 @0
- 测试获取用户admin 被指派的 不限制数量的 executionID为101的 queryID 为0的 调用方法为work 的bug数量 @0
- 测试获取用户admin 被指派的 不限制数量的 executionID为101的 queryID 为1的 调用方法为work 有queryID query值的 的bug数量 @0
- 测试获取用户admin 被指派的 不限制数量的 executionID为0的 queryID 为0的 调用方法为contributeBug 的bug数量 @20
- 测试获取用户admin 被指派的 不限制数量的 executionID为0的 queryID 为0的 调用方法为contributeBug 有queryID query值的 的bug数量 @0

*/

$accountIDList = array('admin', 'dev1');
$typeList      = array('assignedTo', 'closedBy', 'all', 'bySearch');
$limit         = array(0, 10);
$execution     = array(0, 101);
$queryID       = array(0, 1, 1001);
$rawMethod     = array('work', 'contributeBug');
$query         = '`id` < 10';

$bug = new bugModelTest();

r($bug->getUserBugsTest($accountIDList[0], $typeList[0], $limit[0], $execution[0], $queryID[0], $rawMethod[0]))         && p() && e('30'); // 测试获取用户admin 被指派的 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 的bug数量
r($bug->getUserBugsTest($accountIDList[0], $typeList[1], $limit[0], $execution[0], $queryID[0], $rawMethod[0]))         && p() && e('30'); // 测试获取用户admin 关闭的 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 的bug数量
r($bug->getUserBugsTest($accountIDList[0], $typeList[2], $limit[0], $execution[0], $queryID[0], $rawMethod[0]))         && p() && e('0');  // 测试获取用户admin 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 的bug数量
r($bug->getUserBugsTest($accountIDList[0], $typeList[3], $limit[0], $execution[0], $queryID[0], $rawMethod[0]))         && p() && e('90'); // 测试获取用户admin 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 没有queryID 没有query值 的bug数量
r($bug->getUserBugsTest($accountIDList[0], $typeList[3], $limit[0], $execution[0], $queryID[1], $rawMethod[0]))         && p() && e('18'); // 测试获取用户admin 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 有queryID 没有query值 的bug数量
r($bug->getUserBugsTest($accountIDList[0], $typeList[3], $limit[0], $execution[0], $queryID[0], $rawMethod[0], $query)) && p() && e('18'); // 测试获取用户admin 被指派的 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 没有queryID 有query值 的bug数量
r($bug->getUserBugsTest($accountIDList[0], $typeList[3], $limit[0], $execution[0], $queryID[1], $rawMethod[0], $query)) && p() && e('18'); // 测试获取用户admin 被指派的 不限制数量的 executionID为0的 queryID 为1的 调用方法为work 有queryID 有query值的 的bug数量
r($bug->getUserBugsTest($accountIDList[0], $typeList[0], $limit[1], $execution[0], $queryID[0], $rawMethod[0]))         && p() && e('10'); // 测试获取用户admin 被指派的 限制数量10的 executionID为0的 queryID 为0的 调用方法为work 的bug数量
r($bug->getUserBugsTest($accountIDList[0], $typeList[3], $limit[1], $execution[0], $queryID[1], $rawMethod[0], $query)) && p() && e('10'); // 测试获取用户admin 被指派的 限制数量10的 executionID为0的 queryID 为1的 调用方法为work 有queryID query值的 的bug数量
r($bug->getUserBugsTest($accountIDList[0], $typeList[0], $limit[0], $execution[1], $queryID[0], $rawMethod[0]))         && p() && e('3');  // 测试获取用户admin 被指派的 不限制数量的 executionID为101的 queryID 为0的 调用方法为work 的bug数量
r($bug->getUserBugsTest($accountIDList[0], $typeList[3], $limit[0], $execution[1], $queryID[1], $rawMethod[0], $query)) && p() && e('1');  // 测试获取用户admin 被指派的 不限制数量的 executionID为101的 queryID 为1的 调用方法为work 有queryID query值的 的bug数量
r($bug->getUserBugsTest($accountIDList[0], $typeList[0], $limit[0], $execution[0], $queryID[0], $rawMethod[1]))         && p() && e('30'); // 测试获取用户admin 被指派的 不限制数量的 executionID为0的 queryID 为0的 调用方法为contributeBug 的bug数量
r($bug->getUserBugsTest($accountIDList[0], $typeList[3], $limit[0], $execution[0], $queryID[1], $rawMethod[1], $query)) && p() && e('18'); // 测试获取用户admin 被指派的 不限制数量的 executionID为0的 queryID 为0的 调用方法为contributeBug 有queryID query值的 的bug数量

r($bug->getUserBugsTest($accountIDList[1], $typeList[0], $limit[0], $execution[0], $queryID[0], $rawMethod[0]))         && p() && e('20'); // 测试获取用户admin 被指派的 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 的bug数量
r($bug->getUserBugsTest($accountIDList[1], $typeList[1], $limit[0], $execution[0], $queryID[0], $rawMethod[0]))         && p() && e('0');  // 测试获取用户admin 关闭的 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 的bug数量
r($bug->getUserBugsTest($accountIDList[1], $typeList[2], $limit[0], $execution[0], $queryID[0], $rawMethod[0]))         && p() && e('0');  // 测试获取用户admin 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 的bug数量
r($bug->getUserBugsTest($accountIDList[1], $typeList[3], $limit[0], $execution[0], $queryID[0], $rawMethod[0]))         && p() && e('0');  // 测试获取用户admin 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 没有queryID 没有query值 的bug数量
r($bug->getUserBugsTest($accountIDList[1], $typeList[3], $limit[0], $execution[0], $queryID[1], $rawMethod[0]))         && p() && e('0');  // 测试获取用户admin 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 有queryID 没有query值 的bug数量
r($bug->getUserBugsTest($accountIDList[1], $typeList[3], $limit[0], $execution[0], $queryID[0], $rawMethod[0], $query)) && p() && e('0');  // 测试获取用户admin 被指派的 不限制数量的 executionID为0的 queryID 为0的 调用方法为work 没有queryID 有query值 的bug数量
r($bug->getUserBugsTest($accountIDList[1], $typeList[3], $limit[0], $execution[0], $queryID[1], $rawMethod[0], $query)) && p() && e('0');  // 测试获取用户admin 被指派的 不限制数量的 executionID为0的 queryID 为1的 调用方法为work 有queryID 有query值的 的bug数量
r($bug->getUserBugsTest($accountIDList[1], $typeList[0], $limit[1], $execution[0], $queryID[0], $rawMethod[0]))         && p() && e('10'); // 测试获取用户admin 被指派的 限制数量10的 executionID为0的 queryID 为0的 调用方法为work 的bug数量
r($bug->getUserBugsTest($accountIDList[1], $typeList[3], $limit[1], $execution[0], $queryID[1], $rawMethod[0], $query)) && p() && e('0');  // 测试获取用户admin 被指派的 限制数量10的 executionID为0的 queryID 为1的 调用方法为work 有queryID query值的 的bug数量
r($bug->getUserBugsTest($accountIDList[1], $typeList[0], $limit[0], $execution[1], $queryID[0], $rawMethod[0]))         && p() && e('0');  // 测试获取用户admin 被指派的 不限制数量的 executionID为101的 queryID 为0的 调用方法为work 的bug数量
r($bug->getUserBugsTest($accountIDList[1], $typeList[3], $limit[0], $execution[1], $queryID[1], $rawMethod[0], $query)) && p() && e('0');  // 测试获取用户admin 被指派的 不限制数量的 executionID为101的 queryID 为1的 调用方法为work 有queryID query值的 的bug数量
r($bug->getUserBugsTest($accountIDList[1], $typeList[0], $limit[0], $execution[0], $queryID[0], $rawMethod[1]))         && p() && e('20'); // 测试获取用户admin 被指派的 不限制数量的 executionID为0的 queryID 为0的 调用方法为contributeBug 的bug数量
r($bug->getUserBugsTest($accountIDList[1], $typeList[3], $limit[0], $execution[0], $queryID[1], $rawMethod[1], $query)) && p() && e('0');  // 测试获取用户admin 被指派的 不限制数量的 executionID为0的 queryID 为0的 调用方法为contributeBug 有queryID query值的 的bug数量
