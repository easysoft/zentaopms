#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/group.class.php';
su('admin');

/**

title=测试 groupModel->getByID();
cid=1
pid=1

*/

$group = new groupTest();

r($group->getByIDTest()) && p() && e();