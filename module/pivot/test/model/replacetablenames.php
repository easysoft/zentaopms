#!/usr/bin/env php
<?php
/**
title=测试 pivotModel->getWorkload();
cid=1
pid=1

测试传入空字符串的执行结果，也为空字符串    >> 0
测试传入正常的sql语句的执行结果,替换成功    >> select * from zt_user
测试传入非正常的sql语句的执行结果，不替换   >> xxxaaayyy
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';

$pivot = new pivotTest();
$sql = 'select * from TABLE_USER';
$sql1 = 'xxxaaayyy';

$sqlList = array('', $sql, $sql1);

r($pivot->replaceTableNames($sqlList[0])) && p('') && e('0');                       //测试传入空字符串的执行结果，也为空字符串
r($pivot->replaceTableNames($sqlList[1])) && p('') && e('select * from zt_user');   //测试传入正常的sql语句的执行结果,替换成功
r($pivot->replaceTableNames($sqlList[2])) && p('') && e('xxxaaayyy');               //测试传入非正常的sql语句的执行结果，不替换
