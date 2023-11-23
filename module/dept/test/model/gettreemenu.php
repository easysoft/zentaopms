#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/dept.class.php';
su('admin');

zdTable('dept')->gen(30);

/**

title=测试 deptModel->getTreeMenu();
cid=1
pid=1

全部部门树结构查询 >> 产品部
无子部门树结构查询 >> 下级部门
有子部门树结构查询 >> 删除

*/

$deptIDList = array(0, 1, 2);
$userFunc   = array('deptmodel', 'createMemberLink');

$dept = new deptTest();
r($dept->getTreeMenuTest($deptIDList[0], $userFunc)) && p('0:name') && e('产品部1');    //全部部门树结构查询
r($dept->getTreeMenuTest($deptIDList[1], $userFunc)) && p('0:name') && e('产品部1');    //无子部门树结构查询
r($dept->getTreeMenuTest($deptIDList[2], $userFunc)) && p('0:name') && e('开发部2');    //有子部门树结构查询
