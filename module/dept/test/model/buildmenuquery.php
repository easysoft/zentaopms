#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/dept.class.php';
su('admin');

/**

title=测试 deptModel->buildMenuQuery();
cid=1
pid=1

不输入部门id >> SELECT * FROM `zt_dept` ORDER BY `grade` desc,`order` 
输入部门id >> 1

*/

$deptIDList = array('', '2');

$dept = new deptTest();
r($dept->buildMenuQueryTest($deptIDList[0])) && p() && e('SELECT * FROM `zt_dept` ORDER BY `grade` desc,`order` '); //不输入部门id
$sql2 = "SELECT * FROM `zt_dept` WHERE `path`  LIKE ',2,%' ORDER BY `grade` desc,`order` ";
r($dept->buildMenuQueryTest($deptIDList[1]) == $sql2) && p() && e('1');                                             //输入部门id
