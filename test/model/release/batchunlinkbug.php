#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/release.class.php';
su('admin');

/**

title=测试 releaseModel->batchUnlinkBug();
cid=1
pid=1

正常任务批量移除解决的Bug >> 1,
正常任务批量移除遗留的Bug >> 1,
停止维护任务批量移除解决的Bug >> 6,
停止维护任务批量移除遗留的Bug >> 6,

*/

$releaseID = array('1','6');
$bugs      = array('314','315');
$type      = array('bug', 'leftBug');

$release   = new releaseTest();

r($release->batchUnlinkBugTest($releaseID[0], $bugs ,$type[0])) && p('id,bugs,leftBugs') && e('1,'); //正常任务批量移除解决的Bug
r($release->batchUnlinkBugTest($releaseID[0], $bugs ,$type[1])) && p('id,bugs,leftBugs') && e('1,'); //正常任务批量移除遗留的Bug
r($release->batchUnlinkBugTest($releaseID[1], $bugs ,$type[0])) && p('id,bugs,leftBugs') && e('6,'); //停止维护任务批量移除解决的Bug
r($release->batchUnlinkBugTest($releaseID[1], $bugs ,$type[1])) && p('id,bugs,leftBugs') && e('6,'); //停止维护任务批量移除遗留的Bug

