#!/usr/bin/env php
<?php
/**

title=productplanModel->linkStory();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/productplan.class.php';

zdTable('story')->gen(20);

$plan = new productPlan('admin');

$planID = array();
$planID[] = 1;
$planID[] = 100;

$storyIdList = array();
$storyIdList[] = array(4);
$storyIdList[] = array(1111);

r($plan->linkStory($planID[0], $storyIdList[0])) && p('4:plan,story,order') && e('1,4,3'); //关联计划id为1的需求4
r($plan->linkStory($planID[1], $storyIdList[1])) && p()                     && e('0');     //传入不存在id的情况
