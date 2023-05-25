#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testsuite.class.php';
su('admin');

/**

title=测试 testsuiteModel->select();
cid=1
pid=1



*/

$testsuite = new testsuiteTest();

r($testsuite->selectTest()) && p() && e();