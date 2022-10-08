#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testsuite.class.php';
su('admin');

/**

title=测试 testsuiteModel->select();
cid=1
pid=1



*/

$testsuite = new testsuiteTest();

r($testsuite->selectTest()) && p() && e();