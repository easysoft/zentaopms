<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dept.class.php';
su('admin');

/**

title=测试 deptModel->createMemberLink();
cid=1
pid=1

*/

$deptID = '2';

$dept = new deptTest();
r($dept->createMemberLinkTest($deptID)) && p() && e('>开发部<');
