#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dept.class.php';
su('admin');

/**

title=测试 deptModel->getDeptPairs();
cid=1
pid=1

查询全部部门统计 >> 100
查询id为2的部门 >> 开发部
查询id为2的部门数量 >> 100

*/

$deptIDlist = array('0', '2');
$count      = array('0', '1');

$dept = new deptTest();
r($dept->getDeptPairsTest($deptIDlist[0], $count[1])) && p()    && e('100');    //查询全部部门统计
r($dept->getDeptPairsTest($deptIDlist[1], $count[0])) && p('2') && e('开发部'); //查询id为2的部门
r($dept->getDeptPairsTest($deptIDlist[1], $count[1])) && p()    && e('100');    //查询id为2的部门数量