#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';

/**

title=测试 personnelModel->getWhitelistAccount();
cid=1
pid=1

这是一个正常测试，统计关联白名单人员数量 >> 3
取出其中一个数据admin >> admin
取出另一个数据dev10 >> dev10
取出objectid2的白名单数量 >> 0
当objectID不存在时，统计匹配数量 >> 0
当传入参数为空时，统计匹配人员数量 >> 0

*/

$personnel = new personnelTest('admin');

$objectID = array();
$objectID[0] = 1;
$objectID[1] = 2;
$objectID[2] = 1111;
$objectID[3] = '';

$objectType = array();
$objectType[0] = 'project';
$objectType[1] = 'program';
$objectType[2] = 'product';
$objectType[3] = 'sprint';

$result1 = $personnel->getWhitelistAccountTest($objectID[0], $objectType[1]);
$result2 = $personnel->getWhitelistAccountTest($objectID[1], $objectType[2]);
$result3 = $personnel->getWhitelistAccountTest($objectID[2], $objectType[3]);
$result4 = $personnel->getWhitelistAccountTest($objectID[3], $objectType[0]);

r(count($result1)) && p()        && e('3');     //这是一个正常测试，统计关联白名单人员数量
r($result1)        && p('admin') && e('admin'); //取出其中一个数据admin
r($result1)        && p('dev10') && e('dev10'); //取出另一个数据dev10
r(count($result2)) && p()        && e('0');     //取出objectid2的白名单数量
r(count($result3)) && p()        && e('0');     //当objectID不存在时，统计匹配数量
r(count($result4)) && p()        && e('0');     //当传入参数为空时，统计匹配人员数量
