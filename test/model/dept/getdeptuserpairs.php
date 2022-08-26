#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dept.class.php';
su('admin');

/**

title=测试 deptModel->getDeptUserPairs();
cid=1
pid=1

查询所有内部用户 >> 测试1
查询所有外部用户 >> 用户1
根据部门查询用户 >> 测试10
键值为account查询 >> 测试40
键值为out查询 >> 测试10
type为out查询 >> 测试10
查询所有内部用户统计 >> 999
查询所有外部用户统计 >> 1
根据部门查询用户统计 >> 30
查询全部用户统计 >> 1000

*/

$deptIDList = array(0, '2', '5');
$key        = array('id', 'account', 'out');
$type       = array('inside', 'outside', 'out');
$count      = array('0', '1');
$params     = 'all';

$dept = new deptTest();
r($dept->getDeptUserPairsTest($deptIDList[0], $count[0], $key[0], $type[0]))          && p('101')     && e('测试1');  //查询所有内部用户
r($dept->getDeptUserPairsTest($deptIDList[0], $count[0], $key[0], $type[1]))          && p('901')     && e('用户1');  //查询所有外部用户
r($dept->getDeptUserPairsTest($deptIDList[1], $count[0], $key[0], $type[0]))          && p('11')      && e('测试10'); //根据部门查询用户
r($dept->getDeptUserPairsTest($deptIDList[2], $count[0], $key[1], $type[0]))          && p('user40')  && e('测试40'); //键值为account查询
r($dept->getDeptUserPairsTest($deptIDList[1], $count[0], $key[2], $type[0]))          && p('user10')  && e('测试10'); //键值为out查询
r($dept->getDeptUserPairsTest($deptIDList[1], $count[0], $key[1], $type[2]))          && p('user10')  && e('测试10'); //type为out查询
r($dept->getDeptUserPairsTest($deptIDList[0], $count[1], $key[0], $type[0]))          && p()          && e('999');    //查询所有内部用户统计
r($dept->getDeptUserPairsTest($deptIDList[0], $count[1], $key[0], $type[1]))          && p()          && e('1');      //查询所有外部用户统计
r($dept->getDeptUserPairsTest($deptIDList[1], $count[1], $key[0], $type[0]))          && p()          && e('30');     //根据部门查询用户统计
r($dept->getDeptUserPairsTest($deptIDList[0], $count[1], $key[0], $type[0], $params)) && p()          && e('1000');   //查询全部用户统计
