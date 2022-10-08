#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dept.class.php';
su('admin');

/**

title=测试 deptModel->createManageLink();
cid=1
pid=1

编辑链接 >> 编辑
下级部门 >> 下级部门
删除链接 >> 删除
数量 >> orders[5]

*/

$deptID = '5';

$dept = new deptTest();
r($dept->createManageLinkTest($deptID)) && p() && e('编辑');      //编辑链接
r($dept->createManageLinkTest($deptID)) && p() && e('下级部门');  //下级部门
r($dept->createManageLinkTest($deptID)) && p() && e('删除');      //删除链接
r($dept->createManageLinkTest($deptID)) && p() && e('orders[5]'); //数量