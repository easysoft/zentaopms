#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/dept.class.php';
su('admin');

/**

title=测试 deptModel->createManageProjectAdminLink();
cid=1
pid=1

权限分组2开发部链接组成 >> 1

*/

$deptIDList  = array('2', '5');
$groupIDList = array('2', '12');

$dept = new deptTest();
$result = $dept->createManageProjectAdminLinkTest($deptIDList[0], $groupIDList[0]);
$expect = 'createmanageprojectadminlink.php?m=group&f=manageProjectAdmin&groupID=2&deptID=2';

r(strpos($result, $expect) !== false) && p() && e('1'); //权限分组2开发部链接组成
