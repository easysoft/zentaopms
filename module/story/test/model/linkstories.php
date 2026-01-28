#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$story = zenData('story');
$story->product->range(1);
$story->version->range(1);
$story->parent->range('0{18},19');
$story->type->range('requirement{10},story{10}');
$story->gen(20);

zenData('storyspec')->gen(60);
$relation = zenData('relation');
$relation->gen(0);

/**

title=测试 storyModel->linkStories();
timeout=0
cid=18572

- 查看关联前的关联关系数量 @0
- 查看关联后的关联关系数量 @4
- 查看关联后的需求详情
 - 属性1 @1
 - 属性3 @3
 - 属性5 @5
 - 属性7 @7

*/
global $tester;
$tester->loadModel('story');

$beforeRelations = $tester->story->getRelation(1, 'requirement');

$storyIdList = array(1, 3, 5, 7);
$tester->story->linkStories(1, $storyIdList);

$afterRelations = $tester->story->getRelation(1, 'requirement');

r(count($beforeRelations)) && p()          && e('0');       // 查看关联前的关联关系数量
r(count($afterRelations))  && p()          && e('4');       // 查看关联后的关联关系数量
r($afterRelations)         && p('1,3,5,7') && e('1,3,5,7'); // 查看关联后的需求详情