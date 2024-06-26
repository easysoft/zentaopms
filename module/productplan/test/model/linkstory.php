#!/usr/bin/env php
<?php
/**

title=productplanModel->linkStory();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/productplan.unittest.class.php';

zenData('product')->gen(20);
zenData('planstory')->gen(0);
zenData('storyspec')->gen(20);
$story = zenData('story');
$story->version->range('1');
$story->gen(20);

$planID = array();
$planID[] = 2;
$planID[] = 100;

$storyIdList = array();
$storyIdList[] = array(4);
$storyIdList[] = array(1111);

$plan = new productPlan('admin');
r($plan->linkStory($planID[0], $storyIdList[0])) && p('4:plan,story,order') && e('2,4,2'); //关联计划id为2的需求4
r($plan->linkStory($planID[1], $storyIdList[1])) && p()                     && e('0');     //传入不存在id的情况
