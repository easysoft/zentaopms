#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

/**

title=productplanModel->linkStory();
cid=1
pid=1

关联计划id为100的需求4 >> 0
关联计划id为101的需求8 >> 0
传入不存在id的情况 >> 0
解除id为100关联需求4 >> 0
解除id为101关联需求8 >> 0

*/

$plan = new productPlan('admin');

$planID = array();
$planID[0] = 100;
$planID[1] = 101;
$planID[2] = 10000;

$storyID = array();
$storyID[0] = array('stories' => array(4));
$storyID[1] = array('stories' => array(8));
$storyID[2] = array('stories' => array(1111));

$unstoryID = array();
$unstoryID[0] = 4;
$unstoryID[1] = 8;
$unstoryID[2] = 1111;

#方法没有return返回结果，页面上看关联成功了,成功失败均返回空
r($plan->linkStory($planID[0], $storyID[0])) && p() && e('0'); //关联计划id为100的需求4
r($plan->linkStory($planID[1], $storyID[1])) && p() && e('0'); //关联计划id为101的需求8
r($plan->linkStory($planID[2], $storyID[2])) && p() && e('0'); //传入不存在id的情况

#这里将另一个同类方法unlinkStory放在这里调用
r($plan->unlinkStory($unstoryID[0], $planID[0])) && p() && e('0'); //解除id为100关联需求4
r($plan->unlinkStory($unstoryID[1], $planID[1])) && p() && e('0'); //解除id为101关联需求8
?>
