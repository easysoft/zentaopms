#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testsuite.class.php';
su('admin');

zdTable('testsuite')->gen(1);

/**

title=测试 testsuiteModel->deleteSuiteByID();
cid=1
pid=1

测试suiteID值为1 >> 1
测试suiteID值为1000 >> 1
测试suiteID值为0 >> 1

*/
$suiteID = array(1, 1000, 0);

$testsuite = new testsuiteTest();

r($testsuite->deleteSuiteByIDTest($suiteID[0])) && p() && e('1');  //测试suiteID值为1
r($testsuite->deleteSuiteByIDTest($suiteID[1])) && p() && e('1');  //测试suiteID值为1000
r($testsuite->deleteSuiteByIDTest($suiteID[2])) && p() && e('1');  //测试suiteID值为0
