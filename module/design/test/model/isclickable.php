#!/usr/bin/env php
<?php
/**

title=测试 designModel->isClickable();
cid=15994

- 确认needConfirm true 动作 confirmStoryChange 的设计是否可以操作 @1
- 确认needConfirm false 动作 confirmStoryChange 的设计是否可以操作 @0
- 确认needConfirm 不存在 动作 confirmStoryChange 的设计是否可以操作 @0
- 确认needConfirm true 动作 edit 的设计是否可以操作 @1
- 确认needConfirm false 动作 edit 的设计是否可以操作 @1
- 确认needConfirm 不存在 动作 edit 的设计是否可以操作 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('design')->loadYaml('design')->gen(5);

$design1 = new stdClass();
$design1->needConfirm = true;

$design2 = new stdClass();
$design2->needConfirm = false;

$design3 = new stdClass();

$action = array('confirmStoryChange', 'edit');

$designTester = new designModelTest();
r($designTester->isClickableTest($design1, $action[0])) && p() && e('1'); // 确认needConfirm true 动作 confirmStoryChange 的设计是否可以操作
r($designTester->isClickableTest($design2, $action[0])) && p() && e('0'); // 确认needConfirm false 动作 confirmStoryChange 的设计是否可以操作
r($designTester->isClickableTest($design3, $action[0])) && p() && e('0'); // 确认needConfirm 不存在 动作 confirmStoryChange 的设计是否可以操作
r($designTester->isClickableTest($design1, $action[1])) && p() && e('1'); // 确认needConfirm true 动作 edit 的设计是否可以操作
r($designTester->isClickableTest($design2, $action[1])) && p() && e('1'); // 确认needConfirm false 动作 edit 的设计是否可以操作
r($designTester->isClickableTest($design3, $action[1])) && p() && e('1'); // 确认needConfirm 不存在 动作 edit 的设计是否可以操作
