#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/custom.class.php';
su('admin');

/**

title=测试 customModel->saveCustomMenu();
cid=1
pid=1



*/

$custom = new customTest();

r($custom->saveCustomMenuTest()) && p() && e();