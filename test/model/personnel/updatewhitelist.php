#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';

/**

title=测试 personnelModel->updateWhitelist();
cid=1
pid=1

传入已有参数时，数据变更类型source为add >> 0
传入已有参数时，数据变更type为blacklist >> 0
传入没有的参数时，新建一条source为sync的数据 >> 0

*/

$personnel = new personnelTest('admin');

$user = array();
$user[0] = array('admin', 'dev10');
$user[1] = array('test1', 'test2');

$objectType = array();
$objectType[0] = 'project';
$objectType[1] = 'program';
$objectType[2] = 'product';
$objectType[3] = 'sprint';

$objectID = array();
$objectID[0] = 1;
$objectID[1] = 2;

$type = array();
$type[0] = 'whitelist';
$type[1] = 'blacklist';

$source = array();
$source[0] = 'update';
$source[1] = 'add';
$source[2] = 'sync';

$updateType = array();
$updateType[0] = 'increase';
$updateType[1] = 'replace';

$result1 = $personnel->updateWhitelistTest($user[0], $objectType[0], $objectID[1], $type[0], $source[1], $updateType[0]);
$result2 = $personnel->updateWhitelistTest($user[0], $objectType[1], $objectID[0], $type[1], $source[0], $updateType[1]);
$result3 = $personnel->updateWhitelistTest($user[1], $objectType[0], $objectID[0], $type[0], $source[2], $updateType[0]);

r($result1) && p() && e('0'); //传入已有参数时，数据变更类型source为add
r($result2) && p() && e('0'); //传入已有参数时，数据变更type为blacklist
r($result3) && p() && e('0'); //传入没有的参数时，新建一条source为sync的数据