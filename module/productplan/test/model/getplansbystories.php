#!/usr/bin/env php
<?php
/**

title=productpanModel->getPlansByStories();
timeout=0
cid=17638

- 查询存在的id @3
- 查询部分存在的id @1
- 查询不存在的id @0
- 查询空的id @0
- 查询部分空的id @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('productplan')->loadYaml('productplan')->gen(10);
zenData('planstory')->loadYaml('planstory')->gen(10);
zenData('story')->loadYaml('story')->gen(10);
$plan = new productPlan('admin');

$storyIdList = array();
$storyIdList[0] = array(5, 6, 7);
$storyIdList[1] = array(5, 1000);
$storyIdList[2] = array(1000, 1001);
$storyIdList[3] = array();
$storyIdList[4] = array(1, 5);

r($plan->getPlansByStories($storyIdList[0])) && p() && e('3'); //查询存在的id
r($plan->getPlansByStories($storyIdList[1])) && p() && e('1'); //查询部分存在的id
r($plan->getPlansByStories($storyIdList[2])) && p() && e('0'); //查询不存在的id
r($plan->getPlansByStories($storyIdList[3])) && p() && e('0'); //查询空的id
r($plan->getPlansByStories($storyIdList[4])) && p() && e('2'); //查询部分空的id
