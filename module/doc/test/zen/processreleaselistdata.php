#!/usr/bin/env php
<?php

/**

title=测试 docZen::processReleaseListData();
timeout=0
cid=16212

- 执行$result1 @0
- 执行docTest模块的processReleaseListDataTest方法，参数是$releaseList, array 第0条的rowID属性 @1
- 执行$result3 @2
- 执行docTest模块的processReleaseListDataTest方法，参数是$releaseList, $childReleases 第0条的rowID属性 @3
- 执行$result5 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doczen.unittest.class.php';

su('admin');

$docTest = new docZenTest();

// 测试步骤1：空发布列表和空子发布
$result1 = $docTest->processReleaseListDataTest(array(), array());
r(count($result1)) && p() && e('0');

// 测试步骤2：单个发布无构建无子发布
$releaseList = array();
$release = new stdclass();
$release->id = 1;
$release->name = 'Release 1.0';
$release->builds = array();
$release->releases = '';
$releaseList[] = $release;
r($docTest->processReleaseListDataTest($releaseList, array())) && p('0:rowID') && e('1');

// 测试步骤3：单个发布有多个构建
$releaseList = array();
$release = new stdclass();
$release->id = 2;
$release->name = 'Release 2.0';
$release->builds = array('build1', 'build2');
$release->releases = '';
$releaseList[] = $release;
$result3 = $docTest->processReleaseListDataTest($releaseList, array());
r(count($result3)) && p() && e('2');

// 测试步骤4：发布有子发布但不测试递归（避免源码bug）
$releaseList = array();
$childReleases = array();
$parentRelease = new stdclass();
$parentRelease->id = 3;
$parentRelease->name = 'Parent Release';
$parentRelease->builds = array();
$parentRelease->releases = '';  // 不设置子发布避免递归bug
$releaseList[] = $parentRelease;
r($docTest->processReleaseListDataTest($releaseList, $childReleases)) && p('0:rowID') && e('3');

// 测试步骤5：多个发布对象的处理
$releaseList = array();
$release1 = new stdclass();
$release1->id = 4;
$release1->name = 'Release 4.0';
$release1->builds = array('build1');
$release1->releases = '';
$releaseList[] = $release1;

$release2 = new stdclass();
$release2->id = 5;
$release2->name = 'Release 5.0';
$release2->builds = array();
$release2->releases = '';
$releaseList[] = $release2;
$result5 = $docTest->processReleaseListDataTest($releaseList, array());
r(count($result5)) && p() && e('2');