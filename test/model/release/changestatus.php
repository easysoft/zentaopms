#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/release.class.php';
su('admin');

/**

title=测试 releaseModel->changeStatus();
cid=1
pid=1

正常任务批量移除遗留的Bug >> 1,terminate
停止维护任务批量移除解决的Bug >> 6,normal

*/

$releaseID = array('1','6');
$status    = array('normal','terminate');

$release = new releaseTest();

r($release->changeStatusTest($releaseID[0], $status[1])) && p('id,status') && e('1,terminate'); //正常任务批量移除遗留的Bug
r($release->changeStatusTest($releaseID[1], $status[0])) && p('id,status') && e('6,normal');    //停止维护任务批量移除解决的Bug