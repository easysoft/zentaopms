#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dept.class.php';
su('admin');

/**

title=测试 deptModel->getAllChildId();
cid=1
pid=1

有子部门查询 >> 5
无子部门查询 >> 5
子部门数量统计 >> 3
无子部门数量统计 >> 1

*/

$deptIDList = array('2', '5');
$count      = array('0', '1');

$dept = new deptTest();
r($dept->getAllChildIdTest($deptIDList[0], $count[0]))    && p('1') && e('5'); //有子部门查询
r($dept->getAllChildIdTest($deptIDList[1], $count[0])[0]) && p()    && e('5'); //无子部门查询
r($dept->getAllChildIdTest($deptIDList[0], $count[1]))    && p()    && e('3'); //子部门数量统计
r($dept->getAllChildIdTest($deptIDList[1], $count[1]))    && p()    && e('1'); //无子部门数量统计