#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->gitPairs();
cid=1
pid=1

获取GitLab   >> 1

*/

$gitlab = new gitlabTest();

r($gitlab->getPairs()) && p() && e('1');    // 获取GitLab

system("./ztest init");
