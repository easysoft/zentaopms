#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->mergeReviewer();
cid=1
pid=1

获取需求301的评审人 >> admin
获取需求301的待评审人 >> admin
获取产品76下的需求评审人 >> admin
获取产品76下的需求待评审人 >> admin
获取产品76下的需求评审人 >> admin
获取产品76下的需求待评审人 >> admin

*/

global $tester;
$story   = $tester->loadModel('story')->getById(301);
$story   = $tester->story->mergeReviewer($story, true);

$stories = $tester->story->getProductStories(76);
$stories = $tester->story->mergeReviewer($stories);

r($story)                      && p('reviewer:0')  && e('admin'); // 获取需求301的评审人
r($story)                      && p('notReview:0') && e('admin'); // 获取需求301的待评审人
r($stories[302]->reviewer[0])  && p()              && e('admin'); // 获取产品76下的需求评审人
r($stories[302]->notReview[0]) && p()              && e('admin'); // 获取产品76下的需求待评审人
r($stories[304]->reviewer[0])  && p()              && e('admin'); // 获取产品76下的需求评审人
r($stories[304]->notReview[0]) && p()              && e('admin'); // 获取产品76下的需求待评审人