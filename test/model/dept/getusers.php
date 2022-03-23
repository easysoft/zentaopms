#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dept.class.php';
su('admin');

/**

title=测试 deptModel->getUsers();
cid=1
pid=1

全部用户查询 >> outside100,100,其他100
外部用户查询 >> outside1,91,用户1
根据部门查询用户 >> user10,2,测试10
根据account排序 >> user40,5,测试40
全部用户查询统计 >> 999
外部用户查询统计 >> 1
根据部门查询用户统计 >> 10

*/

$deptIDList = array('0', '2', '5');
$browseType = array('inside', 'outside');
$count      = array('0', '1');
$orderBy    = array('id', 'account');

$dept = new deptTest();
r($dept->getUsersTest($browseType[0], $deptIDList[0], $count[0], $orderBy[0])) && p('998:account,dept,realname') && e('outside100,100,其他100'); //全部用户查询
r($dept->getUsersTest($browseType[1], $deptIDList[0], $count[0], $orderBy[0])) && p('0:account,dept,realname')   && e('outside1,91,用户1');      //外部用户查询
r($dept->getUsersTest($browseType[0], $deptIDList[1], $count[0], $orderBy[0])) && p('0:account,dept,realname')   && e('user10,2,测试10');        //根据部门查询用户
r($dept->getUsersTest($browseType[0], $deptIDList[2], $count[0], $orderBy[1])) && p('0:account,dept,realname')   && e('user40,5,测试40');        //根据account排序
r($dept->getUsersTest($browseType[0], $deptIDList[0], $count[1], $orderBy[0])) && p()                            && e('999');                    //全部用户查询统计
r($dept->getUsersTest($browseType[1], $deptIDList[0], $count[1], $orderBy[0])) && p()                            && e('1');                      //外部用户查询统计
r($dept->getUsersTest($browseType[0], $deptIDList[1], $count[1], $orderBy[0])) && p()                            && e('10');                     //根据部门查询用户统计