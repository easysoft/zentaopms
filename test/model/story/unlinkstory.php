#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->unlinkStory();
cid=1
pid=1

删除用户需求25的关联关系之前，获取关联关系数量 >> 1
删除用户需求25的关联关系之后，获取关联关系数量 >> 0

*/

global $tester;
$tester->loadModel('story');

$beforeRelation = $tester->story->getRelation(25, 'requirement');
$tester->story->unlinkStory(25, 26);
$afterRelation = $tester->story->getRelation(25, 'requirement');

r(count($beforeRelation)) && p() && e('1'); //删除用户需求25的关联关系之前，获取关联关系数量
r(count($afterRelation))  && p() && e('0'); //删除用户需求25的关联关系之后，获取关联关系数量
