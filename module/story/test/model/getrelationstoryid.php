#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getStoryRelation();
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('storyspec')->gen(60);
$story = zdTable('story');
$story->product->range(1);
$story->parent->range('0{18},`-1`,19');
$story->type->range('requirement{10},story{10}');
$story->gen(20);

$relation = zdTable('relation');
$relation->product->range(1);
$relation->AID->range('1,11,1,2,12,2,3,13,3,4,14,4,5,15,5,6,16,6,7,17,7,8,18,8');
$relation->BID->range('11,1,1,12,2,2,13,3,3,14,4,4,15,5,5,16,6,6,17,7,7,18,8,8');
$relation->AType->range('requirement,story,design');
$relation->BType->range('story,requirement,commit');
$relation->relation->range('subdivideinto,subdividedfrom,completedin');
$relation->gen(24);

global $tester;
$relations1 = $tester->loadModel('story')->getRelationStoryID(1, 'requirement');
$relations2 = $tester->story->getRelationStoryID(11, 'story');

r($relations1) && p() && e('11'); // 获取用户需求1关联的软件需求
r($relations2) && p() && e('1');  // 获取软件需求11关联的用户需求
