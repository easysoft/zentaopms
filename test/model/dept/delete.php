#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dept.class.php';
su('admin');

/**

title=测试 deptModel->delete();
cid=1
pid=1

删除后统计数量 >> 99

*/

$deptID = '11';

$dept = new deptTest();
r($dept->deleteTest($deptID)) && p() && e('99'); //删除后统计数量

