#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';

/**

title=测试 personnelModel->deleteProgramWhitelist();
cid=1
pid=1

我这里通过add方法创建了一个id为10的项目集白名单，并修改source为同步，然后删除创建的这条信息 >> 0
传入空时这里我删除了一个objectID为0的，program白名单信息，如果没这条数据跳过 >> 0

*/

$personnel = new personnelTest('admin');

$programID = array();
$programID[0] = 10;
$programID[1] = '';

$account   = array();
$account[0]   = 'dev10';
$account[1]   = '';

$result1 = $personnel->deleteProgramWhitelistTest($programID[0], $account[0]);
$result2 = $personnel->deleteProgramWhitelistTest($programID[1], $account[1]);

r($result1) && p() && e('0'); //我这里通过add方法创建了一个id为10的项目集白名单，并修改source为同步，然后删除创建的这条信息
r($result2) && p() && e('0'); //传入空时这里我删除了一个objectID为0的，program白名单信息，如果没这条数据跳过