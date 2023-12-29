#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('job')->config('job')->gen(6);
zdTable('compile')->gen(6);
zdTable('pipeline')->gen(6);
su('admin');

/**

title=测试 compileModel->getLogs();
cid=1
pid=1

- 检查jenkins返回的logs。 @Started by
- 检查gitlab返回的logs。 @<font styl

*/

$compile = $tester->loadModel('compile')->getByID(1);
$job     = $tester->loadModel('job')->getByID($compile->job);
r(substr($tester->compile->getLogs($job, $compile), 0, 10)) && p() && e('Started by'); //检查jenkins返回的logs。

$compile = $tester->loadModel('compile')->getByID(2);
$job     = $tester->loadModel('job')->getByID($compile->job);
r(substr($tester->compile->getLogs($job, $compile), 0, 10)) && p() && e('<font styl'); //检查gitlab返回的logs。
