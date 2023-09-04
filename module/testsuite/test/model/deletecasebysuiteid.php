#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testsuite.class.php';
su('admin');

zdTable('suitecase')->gen(3);
zdTable('testsuite')->gen(1);

/**

title=测试 testsuiteModel->deleteCaseBySuiteIDTest();
cid=1
pid=1

测试suiteID值为1,$cases=array(1, 2) >> 2

*/
$suiteID    = 1;
$cases = array(1, 2);

$testsuite = new testsuiteTest();

r($testsuite->deleteCaseBySuiteIDTest($cases, $suiteID)) && p() && e('2'); //测试suiteID值为1
