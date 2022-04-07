#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/design.class.php';
su('admin');

/**

title=测试 designModel->getPairs();
cid=1
pid=1

*/

$design = new designTest();

r($design->getPairsTest()) && p() && e();