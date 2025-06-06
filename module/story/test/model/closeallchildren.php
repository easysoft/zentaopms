#!/usr/bin/env php
<?php
/**

title=测试 storyModel->closeAllChildren();
timeout=0
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';
su('admin');

$storyTable = zenData('story');
$storyTable->version->range('1');
$storyTable->parent->range('0,1{2},0,4{2},0,7{2},0,10{2},0,13{2},0,16{2},0,19{2}');
$storyTable->gen(21)->fixPath();
zenData('storyspec')->gen(21);

$storyIdList = array(1, 4, 7, 10, 13, 16, 19);
$reasonList  = array('done', 'subdivided', 'duplicate', 'postponed', 'willnotdo', 'cancel', 'bydesign');
