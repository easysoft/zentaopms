#!/usr/bin/env php
<?php
/**

title=测试 buildModel->linkStory();
timeout=0
cid=1

- id为1的build关联0个story第1条的execution属性 @0
- id为1的build关联id为2,4的story第1条的project属性 @11
- id为11的build关联id为2,4的story第11条的execution属性 @101

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

zenData('build')->gen(20);
su('admin');

$buildIDList = array('1', '11');
$stories     = array('2', '4');

$nomalStorylink = $stories;
$noStorylink    = array();

$build = new buildTest();

r($build->linkStoryTest($buildIDList[0], $noStorylink))    && p('1:execution')  && e('0');   // id为1的build关联0个story
r($build->linkStoryTest($buildIDList[0], $nomalStorylink)) && p('1:project')    && e('11');  // id为1的build关联id为2,4的story
r($build->linkStoryTest($buildIDList[1], $nomalStorylink)) && p('11:execution') && e('101'); // id为11的build关联id为2,4的story
