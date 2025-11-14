#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dept.unittest.class.php';
su('admin');

zenData('dept')->loadYaml('dept')->gen(15);
/**

title=测试 deptModel->getAllChildId();
timeout=0
cid=15967

- 有子部门查询 @2
- 无子部门查询 @5
- 子部门数量统计 @1
- 无子部门数量统计 @1
- 不存在的部门子部门数量统计 @0

*/

$deptIDList = array('2', '5', '20');
$count      = array('0', '1');

$dept = new deptTest();
r($dept->getAllChildIdTest($deptIDList[0], $count[0]))    && p('0') && e('2'); //有子部门查询
r($dept->getAllChildIdTest($deptIDList[1], $count[0]))    && p('0') && e('5'); //无子部门查询
r($dept->getAllChildIdTest($deptIDList[0], $count[1]))    && p()    && e('1'); //子部门数量统计
r($dept->getAllChildIdTest($deptIDList[1], $count[1]))    && p()    && e('1'); //无子部门数量统计
r($dept->getAllChildIdTest($deptIDList[2], $count[1]))    && p()    && e('0'); //不存在的部门子部门数量统计
