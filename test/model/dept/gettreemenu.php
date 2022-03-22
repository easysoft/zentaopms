<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dept.class.php';
su('admin');

/**

title=测试 deptModel->getTreeMenu();
cid=1
pid=1

*/

$deptIDList = array('0', '1', '2');
$userFunc   = array('deptmodel', 'createManageLink');

$dept = new deptTest();
r($dept->getTreeMenuTest($deptIDList[0], $userFunc)) && p() && e('<li>开发部 <a');    //全部部门树结构查询
r($dept->getTreeMenuTest($deptIDList[1], $userFunc)) && p() && e('<li>产品部 <a');    //无子部门树结构查询
r($dept->getTreeMenuTest($deptIDList[2], $userFunc)) && p() && e('<li>开发部部1 <a'); //有子部门树结构查询
