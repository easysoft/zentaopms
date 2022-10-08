#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/group.class.php';
su('admin');

/**

title=测试 groupModel->getAccessProgramGroup();
cid=1
pid=1

测试获取group中用户个数 >> 14

*/

$group = new groupTest();
$AccessProgramGroup = $group->getAccessProgramGroupTest();

r(count($AccessProgramGroup)) && p('') && e('14'); //测试获取group中用户个数