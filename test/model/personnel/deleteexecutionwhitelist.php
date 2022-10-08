#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';

/**

title=测试 personnelModel->deleteExecutionWhitelist();
cid=1
pid=1

调用add方法创建spring类型的白名单，并修改source为sync，然后再通过条件删除 >> 0
不传参数的情况，删除id为0的匹配数据，为空则跳过 >> 0

*/

$personnel = new personnelTest('admin');

$executionID = array();
$executionID[0] = 16;
$executionID[1] = '';

$account     = array();
$account[0]  = 'admin';
$account[1]  = '';

$result1 = $personnel->deleteExecutionWhitelistTest($executionID[0], $account[0]);
$result2 = $personnel->deleteExecutionWhitelistTest($executionID[1], $account[1]);

r($result1) && p() && e('0'); //调用add方法创建spring类型的白名单，并修改source为sync，然后再通过条件删除
r($result2) && p() && e('0'); //不传参数的情况，删除id为0的匹配数据，为空则跳过