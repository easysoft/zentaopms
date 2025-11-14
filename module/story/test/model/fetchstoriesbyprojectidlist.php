#!/usr/bin/env php
<?php

/**

title=测试 storyModel->fetchStoriesByProjectIdList();
cid=18492

- 不传入数据。 @1
- 传入不存在的项目ID。 @1
- 检查有关联需求项目的需求数。 @50
- 检查有关联需求项目的需求
 - 第50条的id属性 @50
 - 第50条的product属性 @1
 - 第50条的title属性 @软件需求50
- 检查没有关联需求项目，是否在数据中存在。 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('product')->gen(1);
$projectstory = zenData('projectstory');
$projectstory->project->range('11{50},36{50}');
$projectstory->product->range('1');
$projectstory->story->range('1-50');
$projectstory->gen(100);

$story = zenData('story');
$story->product->range('1');
$story->gen(50);

global $tester;
$storyModel = $tester->loadModel('story');
$storyModel->app->user->admin = true;
r(empty($storyModel->fetchStoriesByProjectIdList(array()))) && p() && e('1');    //不传入数据。
r(empty($storyModel->fetchStoriesByProjectIdList(array(100)))) && p() && e('1'); //传入不存在的项目ID。

$storyGroup = $storyModel->fetchStoriesByProjectIdList(array(11, 12));
r(count($storyGroup[11])) && p()                      && e('50');              // 检查有关联需求项目的需求数。
r($storyGroup[11])        && p('50:id,product,title') && e('50,1,软件需求50'); // 检查有关联需求项目的需求
r(empty($storyGroup[12])) && p()                      && e('1');               // 检查没有关联需求项目，是否在数据中存在。
