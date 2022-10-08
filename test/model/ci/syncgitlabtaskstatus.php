#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/ci.class.php';
su('admin');

/**

title=测试 ciModel->syncGitlabTaskStatus();
cid=1
pid=1



*/

$ci = new ciTest();

r($ci->syncGitlabTaskStatusTest(2)) && p() && e(1); //同步jenkins构建结果