#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->linkStories();
cid=1
pid=1

查看关联前的关联关系数量 >> 0
查看关联后的关联关系数量 >> 4
查看关联后的需求详情 >> 软件需求300,软件需求302,软件需求304,软件需求306

*/

global $tester;
$tester->loadModel('story');

$beforeRelations = $tester->story->getRelation(1, 'requirement');

$storyIdList = array(300, 302, 304, 306);
$_POST['stories'] = $storyIdList;
$tester->story->linkStories(1);

$afterRelations = $tester->story->getRelation(1, 'requirement');

r(count($beforeRelations)) && p()                  && e('0'); // 查看关联前的关联关系数量
r(count($afterRelations))  && p()                  && e('4'); // 查看关联后的关联关系数量
r($afterRelations)         && p('300,302,304,306') && e('软件需求300,软件需求302,软件需求304,软件需求306'); // 查看关联后的需求详情
