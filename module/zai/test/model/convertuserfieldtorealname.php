#!/usr/bin/env php
<?php

/**
title=测试 zaiModel::convertUserFieldToRealname();
timeout=0
cid=0

- story 类型单个用户账号转换为真实姓名 @1
- story 类型多个用户账号转换为真实姓名 @1
- bug 类型用户账号转换为真实姓名 @1
- case 类型 lastRunner 字段转换为真实姓名 @1
- 未知类型返回原对象 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

zenData('user')->gen(3);

su('admin');

global $tester;
$zai = new zaiTest();

/* 获取测试用户信息 */
$userModel = $tester->loadModel('user');
$userPairs = $userModel->getPairs('realname,noletter', '', 0);
$testAccount = '';
$testRealname = '';
foreach($userPairs as $account => $realname)
{
    if($account && $realname)
    {
        $testAccount = $account;
        $testRealname = $realname;
        break;
    }
}

if(empty($testAccount))
{
    $testAccount = 'admin';
    $testRealname = '管理员';
}

/* story 类型单个用户账号转换为真实姓名 */
$story1 = new stdClass();
$story1->id = 1;
$story1->openedBy = $testAccount;
$story1->assignedTo = '';
$convertedStory1 = $zai->convertUserFieldToRealnameTest('story', $story1);
$openedByConverted = ($convertedStory1->openedBy === $testRealname) ? '1' : '0';
r($openedByConverted) && p() && e('1'); // story 类型单个用户账号转换为真实姓名

/* story 类型多个用户账号转换为真实姓名 */
$story2 = new stdClass();
$story2->id = 2;
$story2->openedBy = $testAccount;
$story2->assignedTo = $testAccount;
$convertedStory2 = $zai->convertUserFieldToRealnameTest('story', $story2);
$bothConverted = ($convertedStory2->openedBy === $testRealname && $convertedStory2->assignedTo === $testRealname) ? '1' : '0';
r($bothConverted) && p() && e('1'); // story 类型多个用户账号转换为真实姓名

/* bug 类型用户账号转换为真实姓名 */
$bug1 = new stdClass();
$bug1->id = 1;
$bug1->openedBy = $testAccount;
$bug1->resolvedBy = $testAccount;
$convertedBug1 = $zai->convertUserFieldToRealnameTest('bug', $bug1);
$bugConverted = ($convertedBug1->openedBy === $testRealname && $convertedBug1->resolvedBy === $testRealname) ? '1' : '0';
r($bugConverted) && p() && e('1'); // bug 类型用户账号转换为真实姓名

/* case 类型 lastRunner 字段转换为真实姓名 */
$case1 = new stdClass();
$case1->id = 1;
$case1->openedBy = $testAccount;
$case1->lastRunner = $testAccount;
$convertedCase1 = $zai->convertUserFieldToRealnameTest('case', $case1);
$caseConverted = ($convertedCase1->lastRunner === $testRealname) ? '1' : '0';
r($caseConverted) && p() && e('1'); // case 类型 lastRunner 字段转换为真实姓名

/* 未知类型返回原对象 */
$unknown1 = new stdClass();
$unknown1->id = 1;
$unknown1->openedBy = $testAccount;
$convertedUnknown1 = $zai->convertUserFieldToRealnameTest('unknown', $unknown1);
$unchanged = ($convertedUnknown1->openedBy === $testAccount) ? '1' : '0';
r($unchanged) && p() && e('1'); // 未知类型返回原对象
