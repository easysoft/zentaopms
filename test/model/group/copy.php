#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/group.class.php';
su('admin');

/**

title=测试 groupModel->copy();
cid=1
pid=1

*/

$group = new groupTest();

r($group->copyTest()) && p() && e();