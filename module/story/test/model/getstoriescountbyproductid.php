#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getStoriesCountByProductID();
cid=0

- 检查用户需求草稿状态统计数属性count @1
- 检查用户需求关闭状态统计数属性count @1
- 检查软件需求激活状态统计数属性count @1
- 检查软件需求变更状态统计数属性count @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('story')->gen(10);

global $tester;
$tester->loadModel('story');

$requirementCount = $tester->story->getStoriesCountByProductID(1, 'requirement');
$storyCount       = $tester->story->getStoriesCountByProductID(1, 'story');

r($requirementCount['draft'])  && p('count') && e('1');   //检查用户需求草稿状态统计数
r($requirementCount['closed']) && p('count') && e('1');   //检查用户需求关闭状态统计数
r($storyCount['active'])       && p('count') && e('1');   //检查软件需求激活状态统计数
r($storyCount['changing'])     && p('count') && e('1');   //检查软件需求变更状态统计数
