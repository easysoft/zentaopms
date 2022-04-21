#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';

/**

title=测试 personnelModel->addWhitelist();
cid=1
pid=1

测试正常添加白名单 >> 0
测试不传用户名的情况，这里不传用户名会导致删除符合条件的数据 >> 0

*/

$personnel = new personnelTest('admin');

$user = array();
$user[0] = array('admin');
$user[1] = array();

$objectID = array();
$objectID[0]   = 11;
$objectID[1]   = 21;

$objectType = array();
$objectType[0] = 'project';
$objectType[1] = 'program';
$objectType[2] = 'product';
$objectType[3] = 'sprint';

$result1 = $personnel->addWhitelistTest($objectType[0], $objectID[0], $user[0]);
$result2 = $personnel->addWhitelistTest($objectType[0], $objectID[0], $user[1]);

r($result1) && p() && e('0'); //测试正常添加白名单
r($result2) && p() && e('0'); //测试不传用户名的情况，这里不传用户名会导致删除符合条件的数据