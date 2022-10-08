#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dept.class.php';
su('admin');

/**

title=测试 deptModel->createGroupManageMemberLink();
cid=1
pid=1

权限分组2开发部链接组成 >> dept2

*/

$deptIDList  = array('2', '5');
$groupIDList = array('2', '12');

$dept = new deptTest();
r($dept->createGroupManageMemberLinkTest($deptIDList[0], $groupIDList[0])) && p() && e('dept2'); //权限分组2开发部链接组成