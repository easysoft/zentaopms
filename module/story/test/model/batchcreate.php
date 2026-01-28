#!/usr/bin/env php
<?php

/**

title=测试 storyModel->batchCreate();
timeout=0
cid=18472

- 插入两条软件需求，判断返回的需求总量 @3
- 插入两条用户需求，判断返回的需求总量 @3
- 插入两条软件需求，判断返回的title、type等信息
 - 第1条的title属性 @测试需求1
 - 第1条的type属性 @story
 - 第1条的pri属性 @1.00
 - 第1条的spec属性 @测试需求描述1
 - 第1条的estimate属性 @1
 - 第1条的stage属性 @wait
 - 第1条的module属性 @2221
- 插入两条用户需求，判断返回的title、type等信息
 - 第6条的title属性 @测试需求3
 - 第6条的type属性 @requirement
 - 第6条的pri属性 @3
 - 第6条的spec属性 @测试需求描述3
 - 第6条的estimate属性 @3.00
 - 第6条的stage属性 @wait
 - 第6条的module属性 @2223

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('productplan')->gen(0);
zenData('story')->gen(0);
zenData('storyspec')->gen(0);
zenData('storystage')->gen(0);
zenData('storyreview')->gen(0);
zenData('projectproduct')->gen(0);
zenData('projectstory')->gen(0);
zenData('planstory')->gen(0);

$now   = helper::now();
$story = new storyModelTest();
$stories = array();
$stories['title']      = array(1 => '测试需求1', 2 => '测试需求2', 3 => '测试需求3');
$stories['pri']        = array(1 => 1, 2 => 2, 3 => 3);
$stories['spec']       = array(1 => '测试需求描述1', 2 => '测试需求描述2', 3 => '测试需求描述3');
$stories['verify']     = array(1 => '测试需求验收标准1', 2 => '测试需求验收标准2', 3 => '测试需求验收标准3');
$stories['estimate']   = array(1 => 1, 2 => 2, 3 => 3);
$stories['module']     = array(1 => 2221, 2 => 2222, 3 => 2223);
$stories['plan']       = array(1 => 1, 2 => 2, 3 => 0);
$stories['source']     = array(1 => '', 2 => '', 3 => '');
$stories['sourceNote'] = array(1 => '', 2 => '', 3 => '');
$stories['keywords']   = array(1 => '', 2 => '', 3 => '');
$stories['color']      = array(1 => '', 2 => '', 3 => '');
$stories['openedBy']   = array(1 => 'admin', 2 => 'admin', 3 => 'admin');
$stories['openedDate'] = array(1 => $now, 2 => $now, 3 => $now);

$result1 = $story->batchCreateTest(1, 0, 'story', $stories);
$result2 = $story->batchCreateTest(2, 0, 'requirement', $stories);

r(count($result1)) && p() && e('3'); // 插入两条软件需求，判断返回的需求总量
r(count($result2)) && p() && e('3'); // 插入两条用户需求，判断返回的需求总量
r($result1) && p('1:title,type,pri,spec,estimate,stage,module') && e('测试需求1,story,1,测试需求描述1,1.00,wait,2221');       // 插入两条软件需求，判断返回的title、type等信息
r($result2) && p('6:title,type,pri,spec,estimate,stage,module') && e('测试需求3,requirement,3,测试需求描述3,3.00,wait,2223'); // 插入两条用户需求，判断返回的title、type等信息
