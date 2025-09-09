#!/usr/bin/env php
<?php

/**

title=测试 releaseModel::sendMail2Feedback();
timeout=0
cid=0

- 步骤1：正常情况包含stories和bugs @~~
- 步骤2：只包含stories @~~
- 步骤3：只包含bugs @~~
- 步骤4：不包含stories和bugs @~~
- 步骤5：没有notifyEmail @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

// 创建测试数据 
zenData('story')->loadYaml('story_sendmail2feedback')->gen(10);
zenData('bug')->loadYaml('bug_sendmail2feedback')->gen(10);  
zenData('release')->loadYaml('release_sendmail2feedback')->gen(5);
zenData('user')->gen(5);

su('admin');

global $tester;
$tester->loadModel('release');

// 模拟创建不同场景的release对象
$releases = array();
$releases[1] = new stdClass();
$releases[1]->id = 1;
$releases[1]->name = '版本1.0';
$releases[1]->stories = '';
$releases[1]->bugs = '';

$releases[2] = new stdClass();  
$releases[2]->id = 2;
$releases[2]->name = '版本2.0';
$releases[2]->stories = '1,2,3';
$releases[2]->bugs = '1,2,3';

$releases[3] = new stdClass();
$releases[3]->id = 3; 
$releases[3]->name = '版本3.0';
$releases[3]->stories = '1,4,5';
$releases[3]->bugs = '';

$releases[4] = new stdClass();
$releases[4]->id = 4;
$releases[4]->name = '版本4.0'; 
$releases[4]->stories = '';
$releases[4]->bugs = '6,7,8';

$releases[5] = new stdClass();
$releases[5]->id = 5;
$releases[5]->name = '版本5.0';
$releases[5]->stories = '6,7,8';  // 这些story没有notifyEmail
$releases[5]->bugs = '6,7,8';    // 这些bug没有notifyEmail

// 测试sendMail2Feedback方法  
r($tester->release->sendMail2Feedback($releases[2], '版本发布通知')) && p() && e('~~'); // 步骤1：正常情况包含stories和bugs
r($tester->release->sendMail2Feedback($releases[3], '版本发布通知')) && p() && e('~~'); // 步骤2：只包含stories  
r($tester->release->sendMail2Feedback($releases[4], '版本发布通知')) && p() && e('~~'); // 步骤3：只包含bugs
r($tester->release->sendMail2Feedback($releases[1], '版本发布通知')) && p() && e('~~'); // 步骤4：不包含stories和bugs
r($tester->release->sendMail2Feedback($releases[5], '版本发布通知')) && p() && e('~~'); // 步骤5：没有notifyEmail