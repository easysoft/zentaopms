#!/usr/bin/env php
<?php

/**

title=测试 storyModel->unlinkStory();
timeout=0
cid=18592

- 删除用户需求1的关联关系之前，获取关联关系数量 @1
- 删除用户需求1的关联关系之后，获取关联关系数量 @0
- 需求和需求关联不存在 @0
- 需求和需求关联不存在 @0
- 删除需求12的关联关系之前，获取关联关系数量 @1
- 删除需求12的关联关系之后，获取关联关系数量 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$relation = zenData('relation');
$relation->product->range(1);
$relation->AID->range('1,11,1,2,12,2,3,13,3,4,14,4,5,15,5,6,16,6,7,17,7,8,18,8');
$relation->BID->range('11,1,1,12,2,2,13,3,3,14,4,4,15,5,5,16,6,6,17,7,7,18,8,8');
$relation->AType->range('requirement,story,design');
$relation->BType->range('story,requirement,commit');
$relation->relation->range('linkedto,linkedfrom,completedin');
$relation->gen(24);

global $tester;
$tester->loadModel('story');

$beforeRelation = $tester->story->getRelation(1, 'requirement');
$tester->story->unlinkStory(1, 11);
$afterRelation = $tester->story->getRelation(1, 'requirement');

r(count($beforeRelation)) && p() && e('1'); //删除用户需求1的关联关系之前，获取关联关系数量
r(count($afterRelation))  && p() && e('0'); //删除用户需求1的关联关系之后，获取关联关系数量

$beforeRelation = $tester->story->getRelation(1, 'requirement');
$tester->story->unlinkStory(1, 11);
$afterRelation = $tester->story->getRelation(1, 'requirement');

r(count($beforeRelation)) && p() && e('0'); // 需求和需求关联不存在
r(count($afterRelation))  && p() && e('0'); // 需求和需求关联不存在

$beforeRelation = $tester->story->getRelation(12, 'story');
$tester->story->unlinkStory(12, 2);
$afterRelation = $tester->story->getRelation(12, 'story');

r(count($beforeRelation)) && p() && e('1'); // 删除需求12的关联关系之前，获取关联关系数量
r(count($afterRelation))  && p() && e('0'); // 删除需求12的关联关系之后，获取关联关系数量