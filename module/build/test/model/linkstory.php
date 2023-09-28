#!/usr/bin/env php
<?php
/**

title=测试 buildModel->linkStory();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/build.class.php';

zdTable('build')->gen(20);
su('admin');

$buildIDList = array('1', '11');
$stories     = array('2', '4');

$nomalStorylink = $stories;
$noStorylink    = array();

$build = new buildTest();

r($build->linkStoryTest($buildIDList[0], $noStorylink))    && p('1:stories', '|')            && e('2,4');          // id为1的build关联0个story
r($build->linkStoryTest($buildIDList[0], $nomalStorylink)) && p('1:stories|project', '|')    && e('2,4,|11');       // id为1的build关联id为2,4的story
r($build->linkStoryTest($buildIDList[1], $nomalStorylink)) && p('11:stories|execution', '|') && e('42,44,2,4|101'); // id为11的build关联id为2,4的story
