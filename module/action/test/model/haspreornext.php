#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

zenData('action')->loadYaml('action')->gen(6);
zenData('actionrecent')->gen(0);

su('admin');

/**

title=测试 actionModel->hasPreOrNext();
timeout=0
cid=14915

- 测试传入空参数 @0
- 测试传入空时间 @0
- 测试今天之前是否有动态 @1
- 测试今天之后是否有动态 @1
- 测试指定日期之前是否有动态 @1
- 测试制定日期之后是否有动态 @0

*/

$action = new actionTest();

$dateList      = array('', 'today', date('Y-m-d', strtotime('+3 day')));
$directionList = array('', 'next', 'pre');
r($action->hasPreOrNextTest($dateList[0], $directionList[0])) && p() && e('0');  // 测试传入空参数
r($action->hasPreOrNextTest($dateList[0], $directionList[1])) && p() && e('0');  // 测试传入空时间
r($action->hasPreOrNextTest($dateList[1], $directionList[1])) && p() && e('1');  // 测试今天之前是否有动态
r($action->hasPreOrNextTest($dateList[1], $directionList[2])) && p() && e('1');  // 测试今天之后是否有动态
r($action->hasPreOrNextTest($dateList[2], $directionList[1])) && p() && e('1');  // 测试指定日期之前是否有动态
r($action->hasPreOrNextTest($dateList[2], $directionList[2])) && p() && e('0');  // 测试制定日期之后是否有动态