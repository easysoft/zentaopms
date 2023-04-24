#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/ci.class.php';
su('admin');

/**

title=测试 ciModel->syncGitlabTaskStatus();
cid=1
pid=1



*/

$ci = new ciTest();

r($ci->syncGitlabTaskStatusTest(2)) && p() && e(1); //同步jenkins构建结果