#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/build.class.php';
su('admin');

/**

title=测试 buildModel->linkStory();
cid=1
pid=1

 >> 2,4,,11
 >> ,2,4,101

*/

$buildIDList = array('1', '11');
$stories     = array('2', '4');

$nomalStorylink = array('stories' => $stories);
$noStorylink    = array('stories' => array());

$build = new buildTest();

r($build->linkStoryTest($buildIDList[0], $nomalStorylink)) && p('1:stories,project')    && e('2,4,,11');
r($build->linkStoryTest($buildIDList[1], $nomalStorylink)) && p('11:stories,execution') && e(',2,4,101');
r($build->linkStoryTest($buildIDList[0], $noStorylink))    && p('1:stories')            && e('');

