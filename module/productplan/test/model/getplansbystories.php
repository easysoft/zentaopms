#!/usr/bin/env php
<?php
/**

title=productpanModel->getPlansByStories();
timeout=0
cid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/productplan.class.php';

zdTable('productplan')->config('productplan')->gen(10);
zdTable('planstory')->config('planstory')->gen(10);
zdTable('story')->config('story')->gen(10);
$plan = new productPlan('admin');

$storyIdList = array();
$storyIdList[0] = array(5, 6, 7);
$storyIdList[1] = array(5, 1000);
$storyIdList[2] = array(1000, 1001);

r($plan->getPlansByStories($storyIdList[0])) && p() && e('3'); //查询存在的id
r($plan->getPlansByStories($storyIdList[1])) && p() && e('1'); //查询部分存在的id
r($plan->getPlansByStories($storyIdList[2])) && p() && e('0'); //查询不存在的id
