#!/usr/bin/env php
<?php

/**

title=测试 releaseModel::changeStatus();
timeout=0
cid=17983

- 执行$result1 @1
- 执行$result2 @1
- 执行$result3 @1
- 执行$result4 @1
- 执行$changedRelease @normal

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

// 直接插入测试数据到数据库，避免zenData的字段问题
global $tester;
$tester->dao->exec("TRUNCATE TABLE zt_release");
$tester->dao->exec("INSERT INTO zt_release (id, name, product, status, stories) VALUES
    (1, 'Release 1', 1, 'normal', '1,2'),
    (2, 'Release 2', 1, 'terminate', ''),
    (3, 'Release 3', 1, 'normal', '1')");

zenData('story')->gen(5);
zenData('user')->gen(5);
su('admin');

$release = $tester->loadModel('release');

// 测试步骤1：正常状态改为停止维护
$result1 = $release->changeStatus(1, 'terminate');
r($result1) && p() && e('1');

// 测试步骤2：停止维护状态改为正常
$result2 = $release->changeStatus(2, 'normal');
r($result2) && p() && e('1');

// 测试步骤3：测试带发布日期的状态变更
$result3 = $release->changeStatus(1, 'normal', '2024-03-01');
r($result3) && p() && e('1');

// 测试步骤4：测试不存在发布ID的状态变更
$result4 = $release->changeStatus(999, 'normal');
r($result4) && p() && e('1');

// 测试步骤5：验证状态确实被改变了
$changedRelease = $tester->dao->select('status')->from(TABLE_RELEASE)->where('id')->eq(1)->fetch('status');
r($changedRelease) && p() && e('normal');