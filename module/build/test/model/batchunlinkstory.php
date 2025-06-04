#!/usr/bin/env php
<?php
/**

title=测试 buildModel->batchUnlinkStory();
timeout=0
cid=1

- 批量解除项目版本需求
 - 第1条的project属性 @11
 - 第1条的stories属性 @~~
- 批量解除执行版本需求
 - 第11条的stories属性 @42,44
 - 第11条的execution属性 @101

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';
zenData('build')->gen(20);
zenData('story')->gen(100);
su('admin');

$buildIDList = array('1', '11');
$stories     = array('2', '4');

$build = new buildTest();

r($build->batchUnlinkStoryTest($buildIDList[0], $stories)) && p('1:project|stories', '|')    && e('11|~~');     //批量解除项目版本需求
r($build->batchUnlinkStoryTest($buildIDList[1], $stories)) && p('11:stories|execution', '|') && e('42,44|101'); //批量解除执行版本需求
