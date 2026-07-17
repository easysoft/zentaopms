#!/usr/bin/env php
<?php

/**

title=测试 releaseModel::getReleaseBuildIdList();
timeout=0
cid=18028

- 步骤1：仅 build 字段 @1,2
- 步骤2：build 与 shadow @1,2,99
- 步骤3：仅 shadow @99
- 步骤4：build 首尾逗号 @1,2
- 步骤5：集成子发布 releases @3,4,5,10,11
- 步骤6：build 重复去重 @1,2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
$tester->dao->exec('TRUNCATE TABLE ' . TABLE_RELEASE);
$tester->dao->exec("INSERT INTO " . TABLE_RELEASE . " (id, name, product, status, build, shadow, releases, deleted) VALUES
    (1, 'Child release', 1, 'normal', '3,4', 11, '', 0),
    (2, 'Parent release', 1, 'normal', '5', 10, '1', 0)");

zenData('user')->gen(1);
su('admin');

$releaseTest = new releaseModelTest();

$release1 = new stdclass();
$release1->build     = '1,2';
$release1->shadow    = 0;
$release1->releases  = '';
r($releaseTest->getReleaseBuildIdListTest($release1)) && p() && e('1,2'); // 步骤1：仅 build 字段

$release2 = new stdclass();
$release2->build     = '1,2';
$release2->shadow    = 99;
$release2->releases  = '';
r($releaseTest->getReleaseBuildIdListTest($release2)) && p() && e('1,2,99'); // 步骤2：build 与 shadow

$release3 = new stdclass();
$release3->build     = '';
$release3->shadow    = 99;
$release3->releases  = '';
r($releaseTest->getReleaseBuildIdListTest($release3)) && p() && e('99'); // 步骤3：仅 shadow

$release4 = new stdclass();
$release4->build     = ',1,2,';
$release4->shadow    = 0;
$release4->releases  = '';
r($releaseTest->getReleaseBuildIdListTest($release4)) && p() && e('1,2'); // 步骤4：build 首尾逗号

$release5 = $tester->dao->select('*')->from(TABLE_RELEASE)->where('id')->eq(2)->fetch();
r($releaseTest->getReleaseBuildIdListTest($release5)) && p() && e('3,4,5,10,11'); // 步骤5：集成子发布 releases

$release6 = new stdclass();
$release6->build     = '1,1,2';
$release6->shadow    = 0;
$release6->releases  = '';
r($releaseTest->getReleaseBuildIdListTest($release6)) && p() && e('1,2'); // 步骤6：build 重复去重
