#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getDefaultStages();
cid=0

- 不传入任何数据。 @0
- 传入不是多分支的计划。 @planned
- 传入多分支计划。
 -  @planned
 - 属性1 @planned
- 传入不是多分支的计划，传入关联项目的分支。 @projected
- 传入多分支的计划，传入关联项目的分支。
 -  @projected
 - 属性1 @planned
 - 属性2 @projected

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
su('admin');

$productplan = zenData('productplan');
$productplan->product->range('1,2{3}');
$productplan->branch->range('0,0,1,2');
$productplan->gen(4);

global $tester;
$storyModel = $tester->loadModel('story');
r($storyModel->getDefaultStages('0',   array()))     && p() && e('0');                                  //不传入任何数据。
r($storyModel->getDefaultStages('1',   array()))     && p('0') && e('planned');                         //传入不是多分支的计划。
r($storyModel->getDefaultStages('2,3', array()))     && p('0,1') && e('planned,planned');               //传入多分支计划。
r($storyModel->getDefaultStages('1',   array(0)))    && p('0') && e('projected');                       //传入不是多分支的计划，传入关联项目的分支。
r($storyModel->getDefaultStages('2,3', array(0, 2))) && p('0,1,2') && e('projected,planned,projected'); //传入多分支的计划，传入关联项目的分支。
