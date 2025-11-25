#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getActivateStatus();
cid=18496

- 执行storyModel模块的getActivateStatus方法，参数是1  @active
- 执行storyModel模块的getActivateStatus方法，参数是2  @active
- 执行storyModel模块的getActivateStatus方法，参数是3  @active
- 执行storyModel模块的getActivateStatus方法，参数是4  @changing
- 执行storyModel模块的getActivateStatus方法，参数是5  @active
- 执行storyModel模块的getActivateStatus方法，参数是6  @active
- 执行storyModel模块的getActivateStatus方法，参数是100  @active

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$action = zenData('action');
$action->action->range('open,closed,reviewrejected,closedbysystem,synctwins');
$action->objectType->range('story');
$action->objectID->range('1-100');
$action->execution->range('0');
$action->extra->range('``,2|closed,``,active,6|reviewing,``{1000}');
$action->gen(100);
zenData('story')->gen(50);
$storyreview = zenData('storyreview');
$storyreview->story->range('1-1000');
$storyreview->gen(100);

global $tester;
$storyModel = $tester->loadModel('story');

r($storyModel->getActivateStatus(1))   && p() && e('active');
r($storyModel->getActivateStatus(2))   && p() && e('active');
r($storyModel->getActivateStatus(3))   && p() && e('active');
r($storyModel->getActivateStatus(4))   && p() && e('changing');
r($storyModel->getActivateStatus(5))   && p() && e('active');
r($storyModel->getActivateStatus(6))   && p() && e('active');
r($storyModel->getActivateStatus(100)) && p() && e('active');
