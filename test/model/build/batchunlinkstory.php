#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/build.class.php';
su('admin');

/**

title=测试 buildModel->batchUnlinkStory();
cid=1
pid=1

批量解除项目版本需求 >> ,11
批量解除执行版本需求 >> ,101

*/
$buildIDList = array('1', '11');
$stories     = array('2', '4');

$build = new buildTest();

r($build->batchUnlinkStoryTest($buildIDList[0],$stories)) && p('1:stories,project')    && e(',11');  //批量解除项目版本需求
r($build->batchUnlinkStoryTest($buildIDList[1],$stories)) && p('11:stories,execution') && e(',101'); //批量解除执行版本需求

