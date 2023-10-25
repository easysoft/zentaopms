#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/dept.class.php';
su('admin');

zdTable('dept')->gen(1);

/**

title=测试 deptModel->getByID();
cid=1
pid=1

查找id为1的部门 >> 产品部1
查找id不存在的部门 >> 0

*/

$deptIDList = array('1','0');

$dept = new deptTest();
r($dept->getByIDTest($deptIDList[0])) && p('name') && e('产品部1'); //查找id为1的部门
r($dept->getByIDTest($deptIDList[1])) && p()       && e('0');      //查找id不存在的部门
