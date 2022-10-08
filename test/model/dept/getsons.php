#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dept.class.php';
su('admin');

/**

title=测试 deptModel->getSons();
cid=1
pid=1

有子部门查询 >> 开发部1,2,,2,5,
无子部门查询 >> 0
子部门数量统计 >> 2

*/

$deptIDList = array('2', '5');
$count      = array('0', '1');

$dept = new deptTest();
r($dept->getSonsTest($deptIDList[0], $count[0])) && p('0:name,parent,path') && e('开发部1,2,,2,5,'); //有子部门查询
r($dept->getSonsTest($deptIDList[1], $count[0])) && p()                     && e('0');               //无子部门查询
r($dept->getSonsTest($deptIDList[0], $count[1])) && p()                     && e('2');               //子部门数量统计