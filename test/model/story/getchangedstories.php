#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->getChangedStories();
cid=1
pid=1

获取需求26关联的用户需求数量 >> 1
获取需求26关联的用户需求详情 >> 用户需求25,draft,requirement

*/

global $tester;
$tester->loadModel('story');
$story        = $tester->story->getById(26);
$requirements = $tester->story->getChangedStories($story);

r(count($requirements)) && p()                       && e('1');                            // 获取需求26关联的用户需求数量
r($requirements)        && p('25:title,status,type') && e('用户需求25,draft,requirement'); // 获取需求26关联的用户需求详情