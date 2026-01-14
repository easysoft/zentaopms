#!/usr/bin/env php
<?php

/**

title=测试 storeModel::pickHighestVersion();
timeout=0
cid=18457

- 步骤1：多个版本选择最高属性version @2.1.0
- 步骤2：空列表返回null @0
- 步骤3：单个版本返回该版本属性version @1.0.0
- 步骤4：相同版本返回版本对象属性version @2.0.0
- 步骤5：语义化版本正确比较属性version @1.10.0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$storeTest = new storeModelTest();

// 测试步骤1：传入包含多个版本的版本列表
$version1 = new stdClass();
$version1->version = '1.0.0';
$version1->app_version = 'app-1.0.0';

$version2 = new stdClass();
$version2->version = '2.1.0';
$version2->app_version = 'app-2.1.0';

$version3 = new stdClass();
$version3->version = '1.5.0';
$version3->app_version = 'app-1.5.0';

$multipleVersions = array($version1, $version2, $version3);
r($storeTest->pickHighestVersionTest($multipleVersions)) && p('version') && e('2.1.0'); // 步骤1：多个版本选择最高

// 测试步骤2：传入空版本列表
$emptyVersions = array();
r($storeTest->pickHighestVersionTest($emptyVersions)) && p() && e('0'); // 步骤2：空列表返回null

// 测试步骤3：传入只有一个版本的版本列表
$singleVersion = array($version1);
r($storeTest->pickHighestVersionTest($singleVersion)) && p('version') && e('1.0.0'); // 步骤3：单个版本返回该版本

// 测试步骤4：传入包含相同版本号的版本列表
$sameVersion1 = new stdClass();
$sameVersion1->version = '2.0.0';
$sameVersion1->app_version = 'app-2.0.0-a';

$sameVersion2 = new stdClass();
$sameVersion2->version = '2.0.0';
$sameVersion2->app_version = 'app-2.0.0-b';

$sameVersions = array($sameVersion1, $sameVersion2);
r($storeTest->pickHighestVersionTest($sameVersions)) && p('version') && e('2.0.0'); // 步骤4：相同版本返回版本对象

// 测试步骤5：传入包含语义化版本号的版本列表
$semVer1 = new stdClass();
$semVer1->version = '1.0.0';
$semVer1->app_version = 'app-1.0.0';

$semVer2 = new stdClass();
$semVer2->version = '1.10.0';
$semVer2->app_version = 'app-1.10.0';

$semVer3 = new stdClass();
$semVer3->version = '1.2.5';
$semVer3->app_version = 'app-1.2.5';

$semanticVersions = array($semVer1, $semVer2, $semVer3);
r($storeTest->pickHighestVersionTest($semanticVersions)) && p('version') && e('1.10.0'); // 步骤5：语义化版本正确比较