#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';

/**

title=测试 personnelModel->getWhitelist();
cid=1
pid=1

取出对应object1的项目人员数量 >> 2
取出其中一个人员项目经理10 >> 项目经理10
取出另一个人员admin >> admin
取出对应object2的执行人员数量 >> 0
当传入不存在的objectID时，取出匹配执行人员数量 >> 0
当传入空时，查看匹配执行的人员数量 >> 0

*/

$personnel = new personnelTest('admin');

$objectID = array();
$objectID[0]   = 1;
$objectID[1]   = 2;
$objectID[2]   = 11111;
$objectID[3]   = '';

$objectType = array();
$objectType[0] = 'project';
$objectType[1] = 'program';
$objectType[2] = 'product';
$objectType[3] = 'sprint';

$result1 = $personnel->getWhitelistTest($objectID[0], $objectType[1]);
$result2 = $personnel->getWhitelistTest($objectID[1], $objectType[3]);
$result3 = $personnel->getWhitelistTest($objectID[2], $objectType[3]);
$result4 = $personnel->getWhitelistTest($objectID[3], $objectType[1]);

r(count($result1)) && p()             && e('2');          //取出对应object1的项目人员数量
r($result1)        && p('0:realname') && e('项目经理10'); //取出其中一个人员项目经理10
r($result1)        && p('1:realname') && e('admin');      //取出另一个人员admin
r(count($result2)) && p()             && e('0');          //取出对应object2的执行人员数量
r(count($result3)) && p()             && e('0');          //当传入不存在的objectID时，取出匹配执行人员数量
r(count($result4)) && p()             && e('0');          //当传入空时，查看匹配执行的人员数量