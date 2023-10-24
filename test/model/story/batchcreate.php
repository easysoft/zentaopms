#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->batchCreate();
cid=1
pid=1

插入两条软件需求，判断返回的需求总量 >> 2
插入两条用户需求，判断返回的需求总量 >> 2
插入两条软件需求，判断返回的title、type等信息 >> 测试需求1,story,1,测试需求描述1,1,planned,2221
插入两条用户需求，判断返回的title、type等信息 >> 测试需求2,requirement,2,测试需求描述2,2,planned,2222

*/

$story = new storyTest();
$stories = array();
$stories['title']    = array(1 => '测试需求1', 2 => '测试需求2', 3 => '');
$stories['pri']      = array(1 => 1, 2 => 2, 3 => 3);
$stories['spec']     = array(1 => '测试需求描述1', 2 => '测试需求描述2', 3 => '测试需求描述3');
$stories['verify']   = array(1 => '测试需求验收标准1', 2 => '测试需求验收标准2', 3 => '测试需求验收标准3');
$stories['estimate'] = array(1 => 1, 2 => 2, 3 => 3);
$stories['module']   = array(1 => 2221, 2 => 2222, 3 => 2223);
$stories['plan']     = array(1 => 1, 2 => 2, 3 => 3);

$result1 = $story->batchCreateTest(1, 0, 'story', $stories);
$result2 = $story->batchCreateTest(2, 0, 'requirement', $stories);

r(count($result1)) && p() && e('2'); // 插入两条软件需求，判断返回的需求总量
r(count($result2)) && p() && e('2'); // 插入两条用户需求，判断返回的需求总量
r($result1) && p('401:title,type,pri,spec,estimate,stage,module') && e('测试需求1,story,1,测试需求描述1,1,planned,2221');       // 插入两条软件需求，判断返回的title、type等信息
r($result2) && p('404:title,type,pri,spec,estimate,stage,module') && e('测试需求2,requirement,2,测试需求描述2,2,planned,2222'); // 插入两条用户需求，判断返回的title、type等信息
