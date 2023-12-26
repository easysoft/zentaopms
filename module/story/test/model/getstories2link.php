#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('storyspec')->gen(60);
zdTable('product')->gen(20);
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

/**

title=测试 storyModel->getStories2Link();
cid=1
pid=1

获取需求1可关联的需求数量 >> 2
获取需求2可关联的需求数量 >> 2
获取需求1可关联的需求id、product >> story,1
获取需求2可关联的需求id、product >> requirement,1

*/

global $tester;
$stories1 = $tester->loadModel('story')->getStories2Link(1, 'linkStories', 'bySearch', 0, 'requirement');
$stories2 = $tester->loadModel('story')->getStories2Link(2);

r(count($stories1)) && p()                 && e('9');             // 获取需求1可关联的需求数量
r(count($stories2)) && p()                 && e('3');             // 获取需求2可关联的需求数量
r($stories1)        && p('12:type,product') && e('story,1');       // 获取需求1可关联的需求id、product
r($stories2)        && p('2:type,product') && e('requirement,1'); // 获取需求2可关联的需求id、product
