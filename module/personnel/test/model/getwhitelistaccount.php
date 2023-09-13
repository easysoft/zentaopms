#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';

zdTable('acl')->gen(100);

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

$objectType = array();
$objectType[0] = 'project';
$objectType[1] = 'program';
$objectType[2] = 'product';
$objectType[3] = 'sprint';

$result1 = $personnel->getWhitelistAccountTest($objectID[0], $objectType[1]);
$result2 = $personnel->getWhitelistAccountTest($objectID[1], $objectType[2]);
$result3 = $personnel->getWhitelistAccountTest($objectID[2], $objectType[3]);

r(count($result1)) && p()        && e('5');       //这是一个正常测试，统计关联白名单人员数量
r($result1)        && p('admin') && e('admin');   //取出其中一个数据admin
r($result1)        && p('user20') && e('user20'); //取出另一个数据dev10
r(count($result2)) && p()        && e('5');       //取出objectid2的白名单数量
r(count($result3)) && p()        && e('0');       //当objectID不存在时，统计匹配数量
