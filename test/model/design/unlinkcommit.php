#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/design.class.php';
su('admin');

/**

title=测试 designModel->unlinkCommit();
cid=1
pid=1

*/

$design = new designTest();

r($design->unlinkCommitTest()) && p() && e();