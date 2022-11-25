#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=storyModel->getByAssignedbyme();
cid=1
pid=1

查看产品 1 由我指派的需求 >> 0
查看产品 2 由我指派的需求 >> 0
查看产品 3 由我指派的需求 >> 0
查看产品 4 由我指派的需求 >> 0
查看产品 5 由我指派的需求 >> 0

*/

$productIDList = array(1, 2, 3, 4 ,5);
$branch        = 'all';

$story=new storyTest();

r($story->getByAssignedByTest($productIDList[0], $branch)) && p() && e('0'); // 查看产品 1 由我指派的需求
r($story->getByAssignedByTest($productIDList[1], $branch)) && p() && e('0'); // 查看产品 2 由我指派的需求
r($story->getByAssignedByTest($productIDList[2], $branch)) && p() && e('0'); // 查看产品 3 由我指派的需求
r($story->getByAssignedByTest($productIDList[3], $branch)) && p() && e('0'); // 查看产品 4 由我指派的需求
r($story->getByAssignedByTest($productIDList[4], $branch)) && p() && e('0'); // 查看产品 5 由我指派的需求