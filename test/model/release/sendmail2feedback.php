#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/release.class.php';
su('admin');

/**

title=测试 releaseModel->sendMail2Feedback();
cid=1
pid=1



*/

$release = new releaseTest();

r($release->sendMail2FeedbackTest()) && p() && e();