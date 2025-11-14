#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dept.unittest.class.php';
su('admin');

zenData('dept')->gen(30);
zenData('user')->loadYaml('user')->gen(200);

/**

title=测试 deptModel->getDeptUserPairs();
timeout=0
cid=15972

- 查询所有内部用户属性50 @用户49
- 查询所有外部用户属性101 @测试1
- 根据部门查询用户属性11 @用户10
- 键值为account查询属性user40 @用户40
- 键值为out查询属性user10 @用户10
- type为out查询属性user10 @用户10
- 查询所有内部用户统计 @100
- 查询所有外部用户统计 @100
- 根据部门查询用户统计 @10
- 查询全部用户统计 @200

*/

$deptIDList = array(0, '2', '5');
$key        = array('id', 'account', 'out');
$type       = array('inside', 'outside', 'out');
$count      = array('0', '1');
$params     = 'all';

$dept = new deptTest();
r($dept->getDeptUserPairsTest($deptIDList[0], $count[0], $key[0], $type[0]))          && p('50')      && e('用户49'); //查询所有内部用户
r($dept->getDeptUserPairsTest($deptIDList[0], $count[0], $key[0], $type[1]))          && p('101')     && e('测试1');  //查询所有外部用户
r($dept->getDeptUserPairsTest($deptIDList[1], $count[0], $key[0], $type[0]))          && p('11')      && e('用户10'); //根据部门查询用户
r($dept->getDeptUserPairsTest($deptIDList[2], $count[0], $key[1], $type[0]))          && p('user40')  && e('用户40'); //键值为account查询
r($dept->getDeptUserPairsTest($deptIDList[1], $count[0], $key[2], $type[0]))          && p('user10')  && e('用户10'); //键值为out查询
r($dept->getDeptUserPairsTest($deptIDList[1], $count[0], $key[1], $type[2]))          && p('user10')  && e('用户10'); //type为out查询
r($dept->getDeptUserPairsTest($deptIDList[0], $count[1], $key[0], $type[0]))          && p()          && e('100');    //查询所有内部用户统计
r($dept->getDeptUserPairsTest($deptIDList[0], $count[1], $key[0], $type[1]))          && p()          && e('100');    //查询所有外部用户统计
r($dept->getDeptUserPairsTest($deptIDList[1], $count[1], $key[0], $type[0]))          && p()          && e('10');     //根据部门查询用户统计
r($dept->getDeptUserPairsTest($deptIDList[0], $count[1], $key[0], $type[0], $params)) && p()          && e('200');    //查询全部用户统计
