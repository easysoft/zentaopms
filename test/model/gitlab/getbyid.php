#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->gitById();
cid=1
pid=1

使用存在的ID   >> 3
使用空的ID     >> 0
使用不存在的ID >> 0

*/

$gitlab = new gitlabTest();

$gitlabID = 1;
r($gitlab->getById($gitlabID)) && p('id') && e('1');    // 使用存在的ID

$gitlabID = 0;
r($gitlab->getById($gitlabID)) && p() && e(0);     // 使用空的ID

$gitlabID = 111;
r($gitlab->getById($gitlabID)) && p() && e(0);     // 使用不存在的ID

system("./ztest init");
