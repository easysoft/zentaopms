#!/usr/bin/env php
<?php

/**

title=测试 storyModel->formatStoryForList();
cid=0

- 查看激活之前的需求状态
 - 属性taskCount @1
 - 属性bugCount @2
 - 属性caseCount @3
 - 属性mailto @管理员

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('story')->gen(4);

global $tester;
$tester->loadModel('story');

$story = $tester->story->fetchById(2);
$story->mailto = 'admin,';
$options['storyTasks'][2] = 1;
$options['storyBugs'][2]  = 2;
$options['storyCases'][2] = 3;
$options['users'] = array('admin' => '管理员');

$story = $tester->story->formatStoryForList($story, $options);

r($story) && p('taskCount,bugCount,caseCount,mailto') && e('1,2,3,管理员');      //查看激活之前的需求状态
