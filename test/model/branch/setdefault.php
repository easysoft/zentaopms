#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/branch.class.php';
su('admin');

/**

title=测试 branchModel->setDefault();
cid=1
pid=1

*/

$branch = new branchTest();

r($branch->setDefaultTest()) && p() && e();
