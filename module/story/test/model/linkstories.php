#!/usr/bin/env php
<?php

/**

title=测试 storyModel->linkStories();
cid=0

- 查看关联前的关联关系数量 @0
- 查看关联后的关联关系数量 @4
- 查看关联后的需求详情 @1|3|5|7

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('story')->gen(20);
zdTable('storyspec')->gen(60);
$relation = zdTable('relation');
$relation->gen(0);

global $tester;
$tester->loadModel('story');

$beforeRelations = $tester->story->getRelation(1, 'requirement');

$storyIdList = array(1, 3, 5, 7);
$_POST['stories'] = $storyIdList;
$tester->story->linkStories(1);

$afterRelations = $tester->story->getRelation(1, 'requirement');

r(count($beforeRelations))       && p() && e('0');       // 查看关联前的关联关系数量
r(count($afterRelations))        && p() && e('4');       // 查看关联后的关联关系数量
r(implode('|', $afterRelations)) && p() && e('1|3|5|7'); // 查看关联后的需求详情
