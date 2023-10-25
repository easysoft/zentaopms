#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/dept.class.php';
su('admin');

/**

title=测试 deptModel->createMemberLink();
cid=1
pid=1

获取创建的用户链接 >> 1

*/

$deptID = '2';

$dept = new deptTest();
$result = $dept->createMemberLinkTest($deptID);
$expect = 'creatememberlink.php?m=company&f=browse&browseType=inside&dept=2';
r(strpos($result, $expect) !== false) && p() && e('1');  //获取创建的用户链接
