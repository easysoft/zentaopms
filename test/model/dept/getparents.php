#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dept.class.php';
su('admin');

/**

title=测试 deptModel->getParents();
cid=1
pid=1

父级部门查询父级 >> 2,开发部,0
子级部门查询父级 >> 2,开发部,0
父级部门查询父级统计 >> 1
子级部门查询父级统计 >> 2

*/

$deptIDList = array('2', '5');
$count      = array('0', '1');

$dept = new deptTest();
r($dept->getParentsTest($deptIDList[0], $count[0])) && p('0:id,name,parent') && e('2,开发部,0'); //父级部门查询父级
r($dept->getParentsTest($deptIDList[1], $count[0])) && p('0:id,name,parent') && e('2,开发部,0'); //子级部门查询父级
r($dept->getParentsTest($deptIDList[0], $count[1])) && p()                   && e('1');          //父级部门查询父级统计
r($dept->getParentsTest($deptIDList[1], $count[1])) && p()                   && e('2');          //子级部门查询父级统计