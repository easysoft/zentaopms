#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

$story = zdTable('story');
$story->version->range('1');
$story->gen(20);
zdTable('storyspec')->gen(20);
zdTable('product')->gen(20);

/**

title=测试 storyModel->batchUpdate();
cid=1
pid=1

*/

$story = new storyTest();
$stories = array();
$stories[12]['title']        = '测试软件需求1';
$stories[12]['color']        = '';
$stories[12]['pri']          = 1;
$stories[12]['estimate']     = 1;
$stories[12]['module']       = 2221;
$stories[12]['plan']         = '1';
$stories[12]['stage']        = '1';
$stories[12]['assignedTo']   = 'admin';
$stories[12]['source']       = 'customer';
$stories[12]['sourceNote']   = '测试软件需求来源备注1';
$stories[12]['category']     = 'feature';
$stories[12]['keywords']     = '关键词111';
$stories[12]['closedBy']     = 'admin';
$stories[12]['closedReason'] = 'admin';
$stories[12] = (object)$stories[12];
$stories[14]['title']        = '测试软件需求2';
$stories[14]['color']        = '';
$stories[14]['pri']          = 3;
$stories[14]['estimate']     = 3;
$stories[14]['module']       = 2223;
$stories[14]['plan']         = '3';
$stories[14]['stage']        = '3';
$stories[14]['assignedTo']   = 'test10';
$stories[14]['source']       = 'po';
$stories[14]['sourceNote']   = '测试软件需求来源备注2';
$stories[14]['category']     = 'improve';
$stories[14]['keywords']     = '关键词333';
$stories[14]['closedBy']     = 'admin';
$stories[14]['closedReason'] = 'admin';
$stories[14] = (object)$stories[14];

$requirements[13]['title']        = '测试用户需求1';
$requirements[13]['color']        = '';
$requirements[13]['pri']          = 1;
$requirements[13]['estimate']     = 1;
$requirements[13]['module']       = 2221;
$requirements[13]['plan']         = '1';
$requirements[13]['stage']        = '1';
$requirements[13]['assignedTo']   = 'admin';
$requirements[13]['source']       = 'customer';
$requirements[13]['sourceNote']   = '测试用户需求来源备注1';
$requirements[13]['category']     = 'feature';
$requirements[13]['keywords']     = '关键词111';
$requirements[13]['closedBy']     = 'admin';
$requirements[13]['closedReason'] = 'admin';
$requirements[13] = (object)$requirements[13];
$requirements[15]['title']        = '测试用户需求2';
$requirements[15]['color']        = '';
$requirements[15]['pri']          = 3;
$requirements[15]['estimate']     = 3;
$requirements[15]['module']       = 2223;
$requirements[15]['plan']         = '3';
$requirements[15]['stage']        = '3';
$requirements[15]['assignedTo']   = 'test10';
$requirements[15]['source']       = 'po';
$requirements[15]['sourceNote']   = '测试用户需求来源备注2';
$requirements[15]['category']     = 'improve';
$requirements[15]['keywords']     = '关键词333';
$requirements[15]['closedBy']     = 'admin';
$requirements[15]['closedReason'] = 'admin';
$requirements[15] = (object)$requirements[15];

$result1 = $story->batchUpdateTest($stories);
$result2 = $story->batchUpdateTest($requirements);

r(count($result1)) && p() && e('2'); // 更新两条软件需求，判断返回的需求总量
r(count($result2)) && p() && e('2'); // 更新两条用户需求，判断返回的需求总量
r($result1) && p('14:title,type,pri,sourceNote,estimate,module') && e('测试软件需求2,story,3,测试软件需求来源备注2,3,2223');       // 更新两条软件需求，判断返回的title、type等信息
r($result2) && p('15:title,type,pri,sourceNote,estimate,module') && e('测试用户需求2,requirement,3,测试用户需求来源备注2,3,2223'); // 更新两条用户需求，判断返回的title、type等信息
