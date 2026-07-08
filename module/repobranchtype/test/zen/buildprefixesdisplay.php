#!/usr/bin/env php
<?php

/**

title=测试 repobranchtypeZen::buildPrefixesDisplay 方法();
timeout=0
cid=0

- 测试步骤1：构建前缀显示HTML，验证第一个分支类型 @~~
- 测试步骤2：验证空前缀列表返回空字符串 @~~
- 测试步骤3：验证单个前缀的显示 @~~
- 测试步骤4：验证多个前缀的显示 @~~
- 测试步骤5：验证空数组输入返回空数组 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->gen(5);
zenData('ops_branch_type')->gen(5);

su('admin');

$zenTest = new repobranchtypeZenTest();

// 测试步骤1：构建前缀显示HTML，验证第一个分支类型
$branchType1 = new stdclass();
$branchType1->id       = 1;
$branchType1->prefixes = array('feature/', 'feat/');
$result1 = $zenTest->buildPrefixesDisplayTest(array($branchType1));
r(strpos($result1[0]->prefixesDisplay, 'label') !== false) && p() && e('1');

// 测试步骤2：验证空前缀列表返回空字符串
$branchType2 = new stdclass();
$branchType2->id       = 2;
$branchType2->prefixes = array();
$result2 = $zenTest->buildPrefixesDisplayTest(array($branchType2));
r($result2[0]->prefixesDisplay === '') && p() && e('1');

// 测试步骤3：验证单个前缀的显示
$branchType3 = new stdclass();
$branchType3->id       = 3;
$branchType3->prefixes = array('main/');
$result3 = $zenTest->buildPrefixesDisplayTest(array($branchType3));
r(strpos($result3[0]->prefixesDisplay, 'main/') !== false) && p() && e('1');

// 测试步骤4：验证多个前缀的显示
$branchType4 = new stdclass();
$branchType4->id       = 4;
$branchType4->prefixes = array('dev/', 'develop/', 'story/');
$result4 = $zenTest->buildPrefixesDisplayTest(array($branchType4));
r(strpos($result4[0]->prefixesDisplay, 'dev/') !== false && strpos($result4[0]->prefixesDisplay, 'develop/') !== false) && p() && e('1');

// 测试步骤5：验证空数组输入返回空数组
r(count($zenTest->buildPrefixesDisplayTest(array()))) && p() && e('0');
