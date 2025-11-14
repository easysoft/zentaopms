#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getRevertStoryIdList();
cid=18645

- 不传入数据。 @0
- 传入存在数据的产品 ID。属性1 @1
- 传入不存在数据的产品 ID。 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

$action = zenData('action');
$action->product->range('`,1,`');
$action->action->range('reviewed');
$action->objectType->range('story');
$action->execution->range('0');
$action->objectID->range('1-3');
$action->extra->range('Revert');
$action->gen(10);

global $tester;
$storyModel = $tester->loadModel('story');
r($storyModel->getRevertStoryIdList(0))   && p()  && e('0'); //不传入数据。
r($storyModel->getRevertStoryIdList(1))   && p(1) && e('1'); //传入存在数据的产品 ID。
r($storyModel->getRevertStoryIdList(2))   && p()  && e('0'); //传入不存在数据的产品 ID。
r($storyModel->getRevertStoryIdList(3))   && p()  && e('0'); //传入不存在数据的产品 ID。
r($storyModel->getRevertStoryIdList(100)) && p()  && e('0'); //传入不存在数据的产品 ID。
