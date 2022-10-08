#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dept.class.php';
su('admin');

/**

title=测试 deptModel->buildMenuQuery();
cid=1
pid=1

不输入部门id >> SELECT * FROM `zt_dept` ORDER BY `grade` desc,`order` 
输入部门id >> ,2,

*/

$deptIDList = array('', '2');

$dept = new deptTest();
r($dept->buildMenuQueryTest($deptIDList[0])) && p() && e('SELECT * FROM `zt_dept` ORDER BY `grade` desc,`order` '); //不输入部门id
r($dept->buildMenuQueryTest($deptIDList[1])) && p() && e(',2,');                                                    //输入部门id