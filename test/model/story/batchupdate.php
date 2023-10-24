#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->batchUpdate();
cid=1
pid=1

更新两条软件需求，判断返回的需求总量 >> 2
更新两条用户需求，判断返回的需求总量 >> 2
更新两条软件需求，判断返回的title、type等信息 >> 测试软件需求2,story,3,测试来源备注2,3,2223
更新两条用户需求，判断返回的title、type等信息 >> 测试用户需求2,requirement,3,测试来源备注2,3,2221

*/

$story = new storyTest();
$stories = array();
$stories['titles']      = array(12 => '测试软件需求1', 14 => '测试软件需求2');
$stories['colors']      = array(12 => '', 14 => '');
$stories['pris']        = array(12 => 1, 14 => 3);
$stories['estimates']   = array(12 => 1, 14 => 3);
$stories['modules']     = array(12 => 2221, 14 => 2223);
$stories['plans']       = array(12 => 1, 14 => 3);
$stories['assignedTo']  = array(12 => 'admin', 14 => 'test10');
$stories['sources']     = array(12 => 'customer', 14 => 'po');
$stories['sourceNote']  = array(12 => '测试软件需求来源备注1', 14 => '测试软件需求来源备注2');
$stories['category']    = array(12 => 'feature', 14 => 'improve');
$stories['keywords']    = array(12 => '关键词111', 14 => '关键词333');
$stories['storyIdList'] = array(12, 14);

$requirements['titles']      = array(13 => '测试用户需求1', 15 => '测试用户需求2');
$requirements['colors']      = array(13 => '', 15 => '');
$requirements['pris']        = array(13 => 1, 15 => 3);
$requirements['estimates']   = array(13 => 1, 15 => 3);
$requirements['modules']     = array(13 => 2221, 15 => 2223);
$requirements['plans']       = array(13 => 1, 15 => 3);
$requirements['assignedTo']  = array(13 => 'admin', 15 => 'test10');
$requirements['sources']     = array(13 => 'customer', 15 => 'po');
$requirements['sourceNote']  = array(13 => '测试用户需求来源备注1', 15 => '测试用户需求来源备注2');
$requirements['category']    = array(13 => 'feature', 15 => 'improve');
$requirements['keywords']    = array(13 => '关键词111', 15 => '关键词333');
$requirements['storyIdList'] = array(13, 15);

$result1 = $story->batchUpdateTest($stories);
$result2 = $story->batchUpdateTest($requirements);

r(count($result1)) && p() && e('2'); // 更新两条软件需求，判断返回的需求总量
r(count($result2)) && p() && e('2'); // 更新两条用户需求，判断返回的需求总量
r($result1) && p('14:title,type,pri,sourceNote,estimate,module') && e('测试软件需求2,story,3,测试来源备注2,3,2223');       // 更新两条软件需求，判断返回的title、type等信息
r($result2) && p('15:title,type,pri,sourceNote,estimate,module') && e('测试用户需求2,requirement,3,测试来源备注2,3,2221'); // 更新两条用户需求，判断返回的title、type等信息
