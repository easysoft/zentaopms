#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getChangedStories();
cid=0

- 获取需求12关联的用户需求数量 @1
- 获取需求12关联的用户需求详情
 - 第3条的title属性 @用户需求3
 - 第3条的type属性 @requirement

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('story')->gen(20);
zdTable('product')->gen(1);
$story = zdTable('story');
$story->product->range(1);
$story->version->range(1);
$story->gen(20);

zdTable('storyspec')->gen(20);
$relation = zdTable('relation');
$relation->AID->range('1,10,2,11,3,12,4,13,5,14,6,15,7,16,8,17');
$relation->AVersion->range('1');
$relation->BID->range('10,1,11,2,12,3,13,4,14,5,15,6,16,7,17,8');
$relation->BVersion->range('1');
$relation->gen(16);

global $tester;
$tester->loadModel('story');
$story        = $tester->story->getById(12);
$requirements = $tester->story->getChangedStories($story);

r(count($requirements)) && p()               && e('1');                     // 获取需求12关联的用户需求数量
r($requirements)        && p('3:title,type') && e('用户需求3,requirement'); // 获取需求12关联的用户需求详情
