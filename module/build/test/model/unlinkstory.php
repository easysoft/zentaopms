#!/usr/bin/env php
<?php
/**

title=测试 buildModel->unlinkStory();
timeout=0
cid=15509

- 解除项目版本需求
 - 第1条的project属性 @11
 - 第1条的stories属性 @,4,6
- 解除执行版本需求
 - 第11条的stories属性 @,42,44,2,6
 - 第11条的execution属性 @101
- 解除执行版本需求
 - 第15条的stories属性 @,58,60,2,4
 - 第15条的execution属性 @105

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

zenData('build')->gen(20);
zenData('story')->gen(10);
su('admin');

$buildIDList = array('1', '11', '15');
$stories     = array('2', '4', '6');

$build = new buildTest();

r($build->unlinkStoryTest($buildIDList[0], $stories, $stories[0])) && p('1:project|stories', '|')    && e('11|,4,6');        //解除项目版本需求
r($build->unlinkStoryTest($buildIDList[1], $stories, $stories[1])) && p('11:stories|execution', '|') && e(',42,44,2,6|101'); //解除执行版本需求
r($build->unlinkStoryTest($buildIDList[2], $stories, $stories[2])) && p('15:stories|execution', '|') && e(',58,60,2,4|105'); //解除执行版本需求
