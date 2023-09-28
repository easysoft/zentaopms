#!/usr/bin/env php
<?php
/**

title=测试 buildModel->unlinkStory();
timeout=0
cid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/build.class.php';

zdTable('build')->gen(20);
su('admin');

$buildIDList = array('1', '11');
$stories     = array('2', '4');

$build = new buildTest();

r($build->unlinkStoryTest($buildIDList[0],$stories,$stories[0])) && p('1:stories|project', '|')    && e(',4|11');        //解除项目版本需求
r($build->unlinkStoryTest($buildIDList[1],$stories,$stories[1])) && p('11:stories|execution', '|') && e(',42,44,2|101'); //解除执行版本需求

