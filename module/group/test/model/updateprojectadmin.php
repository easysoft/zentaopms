#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/group.class.php';
su('admin');

/**

title=测试 groupModel->updateProjectAdmin();
cid=1
pid=1



*/

$group = new groupTest();

r($group->updateProjectAdminTest()) && p() && e();