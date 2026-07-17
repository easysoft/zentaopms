#!/usr/bin/env php
<?php

/**

title=测试 releaseModel::getEscapedBugList();
timeout=0
cid=18029

- 步骤1：未发布状态返回空 @0
- 步骤2：无关联版本返回空 @0
- 步骤3：已发布且仅命中发布后关联的 Bug @1
- 步骤4：openedDate 早于发布日不命中 @1
- 步骤5：影响版本不匹配不命中 @1
- 步骤6：产品不一致不命中 @1
- 步骤7：已删除 Bug 不命中 @1
- 步骤8：停止维护状态可查询 @1
- 步骤9：含 shadow 版本命中多条 @1,6
- 步骤10：发布失败状态返回空 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
$tester->dao->exec('TRUNCATE TABLE ' . TABLE_RELEASE);
$tester->dao->exec('TRUNCATE TABLE ' . TABLE_BUG);
$tester->dao->exec("INSERT INTO " . TABLE_RELEASE . " (id, name, product, status, build, shadow, releasedDate, deleted) VALUES
    (1, 'Published release', 1, 'normal', '5', 0, '2024-01-01', 0),
    (2, 'Wait release', 1, 'wait', '5', 0, '2024-01-01', 0),
    (3, 'No build release', 1, 'normal', '', 0, '2024-01-01', 0),
    (4, 'Terminate release', 1, 'terminate', '5', 0, '2024-01-01', 0),
    (5, 'Shadow release', 1, 'normal', '5', 10, '2024-01-01', 0),
    (6, 'Fail release', 1, 'fail', '5', 0, '2024-01-01', 0)");
$tester->dao->exec("INSERT INTO " . TABLE_BUG . " (id, product, title, openedBuild, openedDate, deleted, status, severity, pri) VALUES
    (1, 1, 'Escaped bug', '5', '2024-06-01 10:00:00', 0, 'active', 1, 1),
    (2, 1, 'Before release', '5', '2023-06-01 10:00:00', 0, 'active', 1, 1),
    (3, 1, 'Wrong build', '6', '2024-06-01 10:00:00', 0, 'active', 1, 1),
    (4, 2, 'Wrong product', '5', '2024-06-01 10:00:00', 0, 'active', 1, 1),
    (5, 1, 'Deleted bug', '5', '2024-06-01 10:00:00', 1, 'active', 1, 1),
    (6, 1, 'Shadow build bug', '10', '2024-06-01 10:00:00', 0, 'active', 1, 1)");

zenData('user')->gen(1);
su('admin');

$releaseTest = new releaseModelTest();

$waitRelease = $tester->dao->select('*')->from(TABLE_RELEASE)->where('id')->eq(2)->fetch();
r($releaseTest->getEscapedBugCountTest($waitRelease)) && p() && e('0'); // 步骤1：未发布状态返回空

$noBuildRelease = $tester->dao->select('*')->from(TABLE_RELEASE)->where('id')->eq(3)->fetch();
r($releaseTest->getEscapedBugCountTest($noBuildRelease)) && p() && e('0'); // 步骤2：无关联版本返回空

$publishedRelease = $tester->dao->select('*')->from(TABLE_RELEASE)->where('id')->eq(1)->fetch();
$escapedBugIds    = explode(',', $releaseTest->getEscapedBugListTest($publishedRelease));

r($releaseTest->getEscapedBugListTest($publishedRelease)) && p() && e('1'); // 步骤3：已发布且仅命中发布后关联的 Bug
r(!in_array('2', $escapedBugIds, true)) && p() && e('1'); // 步骤4：openedDate 早于发布日不命中
r(!in_array('3', $escapedBugIds, true)) && p() && e('1'); // 步骤5：影响版本不匹配不命中
r(!in_array('4', $escapedBugIds, true)) && p() && e('1'); // 步骤6：产品不一致不命中
r(!in_array('5', $escapedBugIds, true)) && p() && e('1'); // 步骤7：已删除 Bug 不命中

$terminateRelease = $tester->dao->select('*')->from(TABLE_RELEASE)->where('id')->eq(4)->fetch();
r($releaseTest->getEscapedBugListTest($terminateRelease)) && p() && e('1'); // 步骤8：停止维护状态可查询

$shadowRelease = $tester->dao->select('*')->from(TABLE_RELEASE)->where('id')->eq(5)->fetch();
r($releaseTest->getEscapedBugListTest($shadowRelease)) && p() && e('1,6'); // 步骤9：含 shadow 版本命中多条

$failRelease = $tester->dao->select('*')->from(TABLE_RELEASE)->where('id')->eq(6)->fetch();
r($releaseTest->getEscapedBugCountTest($failRelease)) && p() && e('0'); // 步骤10：发布失败状态返回空
