#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/dept.class.php';
su('admin');

zdTable('dept')->config('dept')->gen(10);

/**

title=测试 deptModel->createManageLink();
cid=1
pid=1

判断是否生成了编辑链接     >> 1
判断是否生成了下级部门链接 >> 1
判断是否生成了删除链接     >> 1
判断是否生成了排序列       >> 1

*/

$deptID = '5';

$dept = new deptTest();
$result = $dept->createManageLinkTest($deptID);

$editLink = 'createmanagelink.php?m=dept&f=edit&deptid=5';
$nextLink = 'createmanagelink.php?m=dept&f=browse&deptid=5';
$deleteLink = 'createmanagelink.php?m=dept&f=delete&deptid=5';
$orderColumn = "id='orders5'";

r(strpos($result, $editLink) !== false)    && p('') && e('1');    //判断是否生成了编辑链接
r(strpos($result, $nextLink) !== false)    && p('') && e('1');    //判断是否生成了下级部门链接
r(strpos($result, $deleteLink) !== false)  && p('') && e('1');    //判断是否生成了删除链接
r(strpos($result, $orderColumn) !== false) && p('') && e('1');    //判断是否生成了排序列
