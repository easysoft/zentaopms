#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';

/**

title=测试 personnelModel->createMemberLink();
cid=1
pid=1

*/

$personnel = new personnelTest();

$programID = array(1, 2);
$deptID    = array(1, 2);

r($personnel->createMemberLinkTest($programID[0], $deptID[0])) && p() && e('1'); // 测试获取项目集 1 部门 1 的访问链接
r($personnel->createMemberLinkTest($programID[0], $deptID[1])) && p() && e('1'); // 测试获取项目集 1 部门 2 的访问链接
r($personnel->createMemberLinkTest($programID[1], $deptID[0])) && p() && e('1'); // 测试获取项目集 2 部门 1 的访问链接
r($personnel->createMemberLinkTest($programID[1], $deptID[1])) && p() && e('1'); // 测试获取项目集 2 部门 2 的访问链接
