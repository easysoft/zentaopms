#!/usr/bin/env php
<?php

/**

title=测试 storyModel->formatStoryForList();
timeout=0
cid=18494

- 查看激活之前的需求状态
 - 属性taskCount @1
 - 属性bugCount @2
 - 属性caseCount @3
 - 属性mailto @管理员
 - 属性rawStatus @active

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('story')->gen(4);

global $tester;
$tester->loadModel('story');

$story = $tester->story->fetchById(2);
$story->mailto = 'admin,';
$options['storyTasks'][2] = 1;
$options['storyBugs'][2]  = 2;
$options['storyCases'][2] = 3;
$options['users'] = array('admin' => '管理员');

$story = $tester->story->formatStoryForList($story, $options, 'story', array('story' => 1));

r($story) && p('taskCount,bugCount,caseCount,mailto,rawStatus') && e('1,2,3,管理员,active'); //查看激活之前的需求状态
