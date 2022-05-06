#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 extensionModel->construct();
cid=1
pid=1

*/

$extension = new extensionTest();

r($extension->constructTest()) && p() && e();
