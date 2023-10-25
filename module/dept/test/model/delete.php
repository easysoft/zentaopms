#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/dept.class.php';
su('admin');

zdTable('dept')->gen(11);

/**

title=测试 deptModel->delete();
cid=1
pid=1

删除后统计数量 >> 99

*/

$deptID = '11';

$dept = new deptTest();
r($dept->deleteTest($deptID)) && p() && e('10'); //删除后统计数量

