#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

$plan = new productPlan('admin');

$storyIdList = array();
$storyIdList[0] = array(5, 6, 7);
$storyIdList[1] = array(5, 1000);
$storyIdList[2] = array(1000, 1001);
r($plan->getPlansByStories($storyIdList[0])) && p() && e('3'); //查询存在的id
r($plan->getPlansByStories($storyIdList[1])) && p() && e('1'); //查询部分存在的id
r($plan->getPlansByStories($storyIdList[2])) && p() && e('0'); //查询不存在的id
?>
