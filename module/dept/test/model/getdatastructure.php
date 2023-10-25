#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/dept.class.php';
su('admin');

zdTable('dept')->gen(30);

/**

title=测试 deptModel->getDataStructure();
cid=1
pid=1

全部查询     >> 30,三级部门10
全部查询统计 >> 30

*/

$count = array('0', '1');

$dept = new deptTest();
r($dept->getDataStructureTest($count[0])) && p('29:id,name')  && e('30,三级部门10'); //全部查询
r($dept->getDataStructureTest($count[1])) && p()             && e('30');             //全部查询统计
