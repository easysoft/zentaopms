<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dept.class.php';
su('admin');

/**

title=测试 deptModel->createManageProjectAdminLink();
cid=1
pid=1

*/

$deptIDList  = array('2', '5');
$groupIDList = array('2', '12');

$dept = new deptTest();
r($dept->createManageProjectAdminLinkTest($deptIDList[0], $groupIDList[0])) && p() && e('开发部');  //权限分组2开发部链接组成
r($dept->createManageProjectAdminLinkTest($deptIDList[1], $groupIDList[1])) && p() && e('开发部1'); //权限分组2开发部链接组成

