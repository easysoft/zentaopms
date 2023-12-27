#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/dept.class.php';

zdTable('dept')->gen(20);
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
$expect = helper::createLink('group', 'manageProjectAdmin', "groupID={$groupIDList[0]}&deptID={$deptIDList[0]}");

r(strpos($result, $expect) !== false) && p() && e('1'); //权限分组2开发部链接组成
