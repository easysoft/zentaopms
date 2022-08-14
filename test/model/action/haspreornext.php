#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->hasPreOrNext();
cid=1
pid=1

测试传入空参数 >> 1
测试空日期之前是否有动态 >> 0
测试空日期之后是否有动态 >> 1
测试今天之前是否有动态 >> 1
测试今天之后是否有动态 >> 0

*/

$action = new actionTest();

$dateList      = array('', 'today');
$directionList = array('', 'next', 'pre');
r($action->hasPreOrNextTest($dateList[0], $directionList[0])) && p() && e('1');  // 测试传入空参数
r($action->hasPreOrNextTest($dateList[0], $directionList[1])) && p() && e('0');  // 测试空日期之前是否有动态
r($action->hasPreOrNextTest($dateList[0], $directionList[2])) && p() && e('1');  // 测试空日期之后是否有动态
r($action->hasPreOrNextTest($dateList[1], $directionList[1])) && p() && e('1');  // 测试今天之前是否有动态
r($action->hasPreOrNextTest($dateList[1], $directionList[2])) && p() && e('0');  // 测试今天之后是否有动态
