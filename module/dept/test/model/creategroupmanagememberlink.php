#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/dept.class.php';
su('admin');

/**

title=测试 deptModel->createGroupManageMemberLink();
cid=1
pid=1

测试权限分组2开发部链接生成是否正确 >> 1

*/

$deptIDList  = array('2', '5');
$groupIDList = array('2', '12');

$dept = new deptTest();
$result = $dept->createGroupManageMemberLinkTest($deptIDList[0], $groupIDList[0]);
$expect = 'creategroupmanagememberlink.php?m=group&f=managemember&groupID=2&deptID=2';

r(strpos($result, $expect) !== false) && p() && e('1'); // 测试权限分组2开发部链接生成是否正确
