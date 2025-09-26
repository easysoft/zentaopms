#!/usr/bin/env php
<?php

/**

title=测试 releaseModel::sendMail2Feedback();
timeout=0
cid=0

- 执行releaseTest模块的sendMail2FeedbackTest方法，参数是$release2, '版本发布通知'  @success
- 执行releaseTest模块的sendMail2FeedbackTest方法，参数是$release3, '版本发布通知'  @success
- 执行releaseTest模块的sendMail2FeedbackTest方法，参数是$release4, '版本发布通知'  @success
- 执行releaseTest模块的sendMail2FeedbackTest方法，参数是$release1, '版本发布通知'  @no_data
- 执行releaseTest模块的sendMail2FeedbackTest方法，参数是$release5, '版本发布通知'  @no_email

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/release.unittest.class.php';

// 创建基础用户数据

su('admin');

$releaseTest = new releaseTest();

// 模拟创建不同场景的release对象
$release1 = new stdClass();
$release1->id = 1;
$release1->name = '版本1.0';
$release1->stories = '';
$release1->bugs = '';

$release2 = new stdClass();
$release2->id = 2;
$release2->name = '版本2.0';
$release2->stories = '1,2,3';
$release2->bugs = '1,2,3';

$release3 = new stdClass();
$release3->id = 3;
$release3->name = '版本3.0';
$release3->stories = '1,4,5';
$release3->bugs = '';

$release4 = new stdClass();
$release4->id = 4;
$release4->name = '版本4.0';
$release4->stories = '';
$release4->bugs = '1,2,3';

$release5 = new stdClass();
$release5->id = 5;
$release5->name = '版本5.0';
$release5->stories = '6,7,8';
$release5->bugs = '6,7,8';

r($releaseTest->sendMail2FeedbackTest($release2, '版本发布通知')) && p() && e('success');
r($releaseTest->sendMail2FeedbackTest($release3, '版本发布通知')) && p() && e('success');
r($releaseTest->sendMail2FeedbackTest($release4, '版本发布通知')) && p() && e('success');
r($releaseTest->sendMail2FeedbackTest($release1, '版本发布通知')) && p() && e('no_data');
r($releaseTest->sendMail2FeedbackTest($release5, '版本发布通知')) && p() && e('no_email');