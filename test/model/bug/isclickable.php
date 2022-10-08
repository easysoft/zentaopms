#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->isClickable();
cid=1
pid=1

状态为active confirmed为0的bug能否执行confirmbug动作 >> 1
状态为active confirmed为0的bug能否执行resolve动作 >> 1
状态为active confirmed为0的bug能否执行close动作 >> 2
状态为active confirmed为0的bug能否执行activate动作 >> 2
状态为active confirmed为0的bug能否执行tostory动作 >> 1
状态为active confirmed为0的bug能否执行test动作 >> 1
状态为active confirmed为1的bug能否执行confirmbug动作 >> 2
状态为active confirmed为1的bug能否执行resolve动作 >> 1
状态为active confirmed为1的bug能否执行close动作 >> 2
状态为active confirmed为1的bug能否执行activate动作 >> 2
状态为active confirmed为1的bug能否执行tostory动作 >> 1
状态为active confirmed为1的bug能否执行test动作 >> 1
状态为resolved的bug能否执行confirmbug动作 >> 2
状态为resolved的bug能否执行resolve动作 >> 2
状态为resolved的bug能否执行close动作 >> 1
状态为resolved的bug能否执行activate动作 >> 1
状态为resolved的bug能否执行tostory动作 >> 2
状态为resolved的bug能否执行test动作 >> 1
状态为closed的bug能否执行confirmbug动作 >> 2
状态为closed的bug能否执行resolve动作 >> 2
状态为closed的bug能否执行close动作 >> 2
状态为closed的bug能否执行activate动作 >> 1
状态为closed的bug能否执行tostory动作 >> 2
状态为closed的bug能否执行test动作 >> 1

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

$actionList = array('confirmbug', 'resolve', 'close', 'activate', 'tostory', 'test');

$bug=new bugTest();

r($bug->isClickableTest($object1, $actionList[0])) && p() && e('1'); // 状态为active confirmed为0的bug能否执行confirmbug动作
r($bug->isClickableTest($object1, $actionList[1])) && p() && e('1'); // 状态为active confirmed为0的bug能否执行resolve动作
r($bug->isClickableTest($object1, $actionList[2])) && p() && e('2'); // 状态为active confirmed为0的bug能否执行close动作
r($bug->isClickableTest($object1, $actionList[3])) && p() && e('2'); // 状态为active confirmed为0的bug能否执行activate动作
r($bug->isClickableTest($object1, $actionList[4])) && p() && e('1'); // 状态为active confirmed为0的bug能否执行tostory动作
r($bug->isClickableTest($object1, $actionList[5])) && p() && e('1'); // 状态为active confirmed为0的bug能否执行test动作
r($bug->isClickableTest($object2, $actionList[0])) && p() && e('2'); // 状态为active confirmed为1的bug能否执行confirmbug动作
r($bug->isClickableTest($object2, $actionList[1])) && p() && e('1'); // 状态为active confirmed为1的bug能否执行resolve动作
r($bug->isClickableTest($object2, $actionList[2])) && p() && e('2'); // 状态为active confirmed为1的bug能否执行close动作
r($bug->isClickableTest($object2, $actionList[3])) && p() && e('2'); // 状态为active confirmed为1的bug能否执行activate动作
r($bug->isClickableTest($object2, $actionList[4])) && p() && e('1'); // 状态为active confirmed为1的bug能否执行tostory动作
r($bug->isClickableTest($object2, $actionList[5])) && p() && e('1'); // 状态为active confirmed为1的bug能否执行test动作
r($bug->isClickableTest($object3, $actionList[0])) && p() && e('2'); // 状态为resolved的bug能否执行confirmbug动作
r($bug->isClickableTest($object3, $actionList[1])) && p() && e('2'); // 状态为resolved的bug能否执行resolve动作
r($bug->isClickableTest($object3, $actionList[2])) && p() && e('1'); // 状态为resolved的bug能否执行close动作
r($bug->isClickableTest($object3, $actionList[3])) && p() && e('1'); // 状态为resolved的bug能否执行activate动作
r($bug->isClickableTest($object3, $actionList[4])) && p() && e('2'); // 状态为resolved的bug能否执行tostory动作
r($bug->isClickableTest($object3, $actionList[5])) && p() && e('1'); // 状态为resolved的bug能否执行test动作
r($bug->isClickableTest($object4, $actionList[0])) && p() && e('2'); // 状态为closed的bug能否执行confirmbug动作
r($bug->isClickableTest($object4, $actionList[1])) && p() && e('2'); // 状态为closed的bug能否执行resolve动作
r($bug->isClickableTest($object4, $actionList[2])) && p() && e('2'); // 状态为closed的bug能否执行close动作
r($bug->isClickableTest($object4, $actionList[3])) && p() && e('1'); // 状态为closed的bug能否执行activate动作
r($bug->isClickableTest($object4, $actionList[4])) && p() && e('2'); // 状态为closed的bug能否执行tostory动作
r($bug->isClickableTest($object4, $actionList[5])) && p() && e('1'); // 状态为closed的bug能否执行test动作