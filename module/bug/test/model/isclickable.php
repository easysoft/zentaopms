#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);

su('admin');

/**

title=bugModel->isClickable();
timeout=0
cid=15401

- 状态为active confirmed为0的bug能否执行confirm动作 @1
- 状态为active confirmed为0的bug能否执行resolve动作 @1
- 状态为active confirmed为0的bug能否执行close动作 @2
- 状态为active confirmed为0的bug能否执行activate动作 @2
- 状态为active confirmed为0的bug能否执行tostory动作 @1
- 状态为active confirmed为0的bug能否执行test动作 @1
- 状态为active confirmed为1的bug能否执行confirm动作 @2
- 状态为active confirmed为1的bug能否执行resolve动作 @1
- 状态为active confirmed为1的bug能否执行close动作 @2
- 状态为active confirmed为1的bug能否执行activate动作 @2
- 状态为active confirmed为1的bug能否执行tostory动作 @1
- 状态为active confirmed为1的bug能否执行test动作 @1
- 状态为resolved的bug能否执行confirm动作 @2
- 状态为resolved的bug能否执行resolve动作 @2
- 状态为resolved的bug能否执行close动作 @1
- 状态为resolved的bug能否执行activate动作 @1
- 状态为resolved的bug能否执行tostory动作 @2
- 状态为resolved的bug能否执行test动作 @1
- 状态为closed的bug能否执行confirm动作 @2
- 状态为closed的bug能否执行resolve动作 @2
- 状态为closed的bug能否执行close动作 @2
- 状态为closed的bug能否执行activate动作 @1
- 状态为closed的bug能否执行tostory动作 @2
- 状态为closed的bug能否执行test动作 @1

*/

$object1 = new stdclass();
$object1->status   = 'active';
$object1->confirmed = 0;

$object2 = new stdclass();
$object2->status   = 'active';
$object2->confirmed = 1;

$object3 = new stdclass();
$object3->status = 'resolved';

$object4 = new stdclass();
$object4->status = 'closed';

$actionList = array('confirm', 'resolve', 'close', 'activate', 'tostory', 'test');

$bug=new bugModelTest();

r($bug->isClickableTest($object1, $actionList[0])) && p() && e('1'); // 状态为active confirmed为0的bug能否执行confirm动作
r($bug->isClickableTest($object1, $actionList[1])) && p() && e('1'); // 状态为active confirmed为0的bug能否执行resolve动作
r($bug->isClickableTest($object1, $actionList[2])) && p() && e('2'); // 状态为active confirmed为0的bug能否执行close动作
r($bug->isClickableTest($object1, $actionList[3])) && p() && e('2'); // 状态为active confirmed为0的bug能否执行activate动作
r($bug->isClickableTest($object1, $actionList[4])) && p() && e('1'); // 状态为active confirmed为0的bug能否执行tostory动作
r($bug->isClickableTest($object1, $actionList[5])) && p() && e('1'); // 状态为active confirmed为0的bug能否执行test动作
r($bug->isClickableTest($object2, $actionList[0])) && p() && e('2'); // 状态为active confirmed为1的bug能否执行confirm动作
r($bug->isClickableTest($object2, $actionList[1])) && p() && e('1'); // 状态为active confirmed为1的bug能否执行resolve动作
r($bug->isClickableTest($object2, $actionList[2])) && p() && e('2'); // 状态为active confirmed为1的bug能否执行close动作
r($bug->isClickableTest($object2, $actionList[3])) && p() && e('2'); // 状态为active confirmed为1的bug能否执行activate动作
r($bug->isClickableTest($object2, $actionList[4])) && p() && e('1'); // 状态为active confirmed为1的bug能否执行tostory动作
r($bug->isClickableTest($object2, $actionList[5])) && p() && e('1'); // 状态为active confirmed为1的bug能否执行test动作
r($bug->isClickableTest($object3, $actionList[0])) && p() && e('2'); // 状态为resolved的bug能否执行confirm动作
r($bug->isClickableTest($object3, $actionList[1])) && p() && e('2'); // 状态为resolved的bug能否执行resolve动作
r($bug->isClickableTest($object3, $actionList[2])) && p() && e('1'); // 状态为resolved的bug能否执行close动作
r($bug->isClickableTest($object3, $actionList[3])) && p() && e('1'); // 状态为resolved的bug能否执行activate动作
r($bug->isClickableTest($object3, $actionList[4])) && p() && e('2'); // 状态为resolved的bug能否执行tostory动作
r($bug->isClickableTest($object3, $actionList[5])) && p() && e('1'); // 状态为resolved的bug能否执行test动作
r($bug->isClickableTest($object4, $actionList[0])) && p() && e('2'); // 状态为closed的bug能否执行confirm动作
r($bug->isClickableTest($object4, $actionList[1])) && p() && e('2'); // 状态为closed的bug能否执行resolve动作
r($bug->isClickableTest($object4, $actionList[2])) && p() && e('2'); // 状态为closed的bug能否执行close动作
r($bug->isClickableTest($object4, $actionList[3])) && p() && e('1'); // 状态为closed的bug能否执行activate动作
r($bug->isClickableTest($object4, $actionList[4])) && p() && e('2'); // 状态为closed的bug能否执行tostory动作
r($bug->isClickableTest($object4, $actionList[5])) && p() && e('1'); // 状态为closed的bug能否执行test动作
