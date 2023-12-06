#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';
su('admin');

zdTable('project')->config('execution')->gen(10);
zdTable('task')->gen(20);
zdTable('user')->gen(2);
/**
title=测试 pivotModel->getExecutions();

获取全部的执行              >> 3,33.33
获取3个月前到1年后的执行    >> 3,33.33
获取6个月前到4个月前的执行  >> 0,0
获取用户user1的全部执行     >> 3,n/a

*/

$pivot = new pivotTest();

$date1_start = date('Y-m-d', strtotime('-3 months'));
$date1_end   = date('Y-m-d', strtotime('+1 years'));
$date2_start = date('Y-m-d', strtotime('-6 months'));
$date2_end   = date('Y-m-d', strtotime('-4 months'));
$startList = array('', $date1_start, $date2_start);
$endList   = array('', $date1_end,   $date2_end);

r($pivot->getExecutions($startList[0], $endList[0])) && p('0:deviation,deviationRate') && e('3,33.33');  //获取全部的执行
r($pivot->getExecutions($startList[1], $endList[1])) && p('0:deviation,deviationRate') && e('3,33.33');  //获取3个月前到1年后的执行
r($pivot->getExecutions($startList[2], $endList[2])) && p('0:deviation,deviationRate') && e('0,0');      //获取6个月前到4个月前的执行

su('user1');

r($pivot->getExecutions($startList[0], $endList[0])) && p('0:deviation,deviationRate') && e('3,n/a');  //获取用户user1的全部执行
