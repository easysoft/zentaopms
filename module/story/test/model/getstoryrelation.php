#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getStoryRelation();
timeout=0
cid=18560

- 获取用户需求1关联的软件需求数量 @1
- 获取软件需求11关联的用户需求数量 @1
- 获取用户需求1关联的软件需求详情
 - 第11条的title属性 @用户需求11
 - 第11条的type属性 @story
 - 第11条的status属性 @closed
- 获取软件需求11关联的用户需求详情
 - 第1条的title属性 @用户需求1
 - 第1条的type属性 @requirement
 - 第1条的status属性 @draft

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('storyspec')->gen(60);
$story = zenData('story');
$story->product->range(1);
$story->parent->range('0{18},19');
$story->type->range('requirement{10},story{10}');
$story->gen(20);

$relation = zenData('relation');
$relation->product->range(1);
$relation->AID->range('1,11,1,2,12,2,3,13,3,4,14,4,5,15,5,6,16,6,7,17,7,8,18,8');
$relation->BID->range('11,1,1,12,2,2,13,3,3,14,4,4,15,5,5,16,6,6,17,7,7,18,8,8');
$relation->AType->range('requirement,story,design');
$relation->BType->range('story,requirement,commit');
$relation->relation->range('linkedto,linkedfrom,completedin');
$relation->gen(24);

global $tester;
$relations1 = $tester->loadModel('story')->getStoryRelation(1, 'requirement');
$relations2 = $tester->story->getStoryRelation(11, 'story');

r(count($relations1))         && p()                       && e('1');                           // 获取用户需求1关联的软件需求数量
r(count($relations2))         && p()                       && e('1');                           // 获取软件需求11关联的用户需求数量
r($relations1['story'])       && p('11:title,type,status') && e('用户需求11,story,closed');     // 获取用户需求1关联的软件需求详情
r($relations2['requirement']) && p('1:title,type,status')  && e('用户需求1,requirement,draft'); // 获取软件需求11关联的用户需求详情