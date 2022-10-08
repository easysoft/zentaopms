#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/group.class.php';
su('admin');

/**

title=测试 groupModel->copyPriv();
cid=1
pid=1

复制分组6权限到分组7 >> 1

*/

$fromGroup = 6;
$toGroup   = 7;

$group = new groupTest();
r($group->copyPrivTest($fromGroup, $toGroup)) && p() && e('1'); // 复制分组6权限到分组7