#!/usr/bin/env php
<?php

/**

title=测试 repoZen::checkACL();
timeout=0
cid=18129

- acl数组正常返回 @1
- acl数组正常返回 @1
- 白名单不能为空属性acl @『白名单』不能为空。
- acl数组正常返回 @1
- acl数组正常返回 @1
- acl数组正常返回 @1
- 白名单不能为空属性acl @『白名单』不能为空。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

su('admin');

$repoTest = new repoZenTest();

// 测试步骤1: acl为open,返回acl数组
dao::$errors = array();
$postData1 = array('acl' => array('acl' => 'open', 'groups' => array(), 'users' => array()));
$result1 = $repoTest->checkACLTest($postData1);
r(!empty($result1) && is_array($result1)) && p() && e('1'); // acl数组正常返回

// 测试步骤2: acl为private,返回acl数组
dao::$errors = array();
$postData2 = array('acl' => array('acl' => 'private', 'groups' => array(), 'users' => array()));
$result2 = $repoTest->checkACLTest($postData2);
r(!empty($result2) && is_array($result2)) && p() && e('1'); // acl数组正常返回

// 测试步骤3: acl为custom且groups和users都为空,返回错误
dao::$errors = array();
$postData3 = array('acl' => array('acl' => 'custom', 'groups' => array(), 'users' => array()));
$result3 = $repoTest->checkACLTest($postData3);
r($result3) && p('acl') && e('『白名单』不能为空。'); // 白名单不能为空

// 测试步骤4: acl为custom且仅设置groups,返回acl数组
dao::$errors = array();
$postData4 = array('acl' => array('acl' => 'custom', 'groups' => array('1', '2'), 'users' => array()));
$result4 = $repoTest->checkACLTest($postData4);
r(!empty($result4) && is_array($result4) && isset($result4['acl'])) && p() && e('1'); // acl数组正常返回

// 测试步骤5: acl为custom且仅设置users,返回acl数组
dao::$errors = array();
$postData5 = array('acl' => array('acl' => 'custom', 'groups' => array(), 'users' => array('admin', 'user1')));
$result5 = $repoTest->checkACLTest($postData5);
r(!empty($result5) && is_array($result5) && isset($result5['acl'])) && p() && e('1'); // acl数组正常返回

// 测试步骤6: acl为custom且同时设置groups和users,返回acl数组
dao::$errors = array();
$postData6 = array('acl' => array('acl' => 'custom', 'groups' => array('1', '2'), 'users' => array('admin', 'user1')));
$result6 = $repoTest->checkACLTest($postData6);
r(!empty($result6) && is_array($result6) && isset($result6['acl'])) && p() && e('1'); // acl数组正常返回

// 测试步骤7: acl为custom且groups包含空字符串,users为空,返回错误
dao::$errors = array();
$postData7 = array('acl' => array('acl' => 'custom', 'groups' => array('', ''), 'users' => array()));
$result7 = $repoTest->checkACLTest($postData7);
r($result7) && p('acl') && e('『白名单』不能为空。'); // 白名单不能为空