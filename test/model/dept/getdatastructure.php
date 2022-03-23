#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dept.class.php';
su('admin');

/**

title=测试 deptModel->getDataStructure();
cid=1
pid=1

全部查询 >> 100,其他部门63
全部查询统计 >> 78

*/

$count = array('0', '1');

$dept = new deptTest();
r($dept->getDataStructureTest($count[0])) && p('77:id,name') && e('100,其他部门63'); //全部查询
r($dept->getDataStructureTest($count[1])) && p()             && e('78');             //全部查询统计