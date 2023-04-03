#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/release.class.php';
su('admin');

/**

title=测试 releaseModel->batchUnlinkStory();
cid=1
pid=1

正常任务批量移除关联需求 >> 1,
停止维护任务批量移除关联需求 >> 6,

*/

$releaseID = array('1','6');
$stories   = array('2','4');

$release   = new releaseTest();

r($release->batchUnlinkStoryTest($releaseID[0],$stories)) && p('id,stories') && e('1,'); //正常任务批量移除关联需求
r($release->batchUnlinkStoryTest($releaseID[1],$stories)) && p('id,stories') && e('6,'); //停止维护任务批量移除关联需求

