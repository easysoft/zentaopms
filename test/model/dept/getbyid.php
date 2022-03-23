#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dept.class.php';
su('admin');

/**

title=测试 deptModel->getByID();
cid=1
pid=1

查找id为1的部门 >> 产品部
查找id不存在的部门 >> 0

*/

$deptIDList = array('1','0');

$dept = new deptTest();
r($dept->getByIDTest($deptIDList[0])) && p('name') && e('产品部'); //查找id为1的部门
r($dept->getByIDTest($deptIDList[1])) && p()       && e('0');      //查找id不存在的部门