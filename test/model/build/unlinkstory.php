#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/build.class.php';
su('admin');

/**

title=测试 buildModel->unlinkStory();
cid=1
pid=1

解除项目版本需求 >> ,4,11
解除执行版本需求 >> ,2,101

*/
$buildIDList = array('1', '11');
$stories     = array('2', '4');

$build = new buildTest();

r($build->unlinkStoryTest($buildIDList[0],$stories,$stories[0])) && p('1:stories,project')    && e(',4,11');                 //解除项目版本需求
r($build->unlinkStoryTest($buildIDList[1],$stories,$stories[1])) && p('11:stories,execution') && e(',2,101');                //解除执行版本需求

