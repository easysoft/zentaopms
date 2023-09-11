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

检查id为1的套件是否存在  >> 1:0
测试suiteID值为1         >> 1
检查id为1的套件是否存在2 >> 1:1

*/
$suiteID = 1;

$testsuite = new testsuiteTest();

r($testsuite->getByIdTest($suiteID))             && p('id;deleted') && e('1;0');  //检查id为1的套件是否存在
r($testsuite->deleteSuiteByIDTest($suiteID))     && p('')           && e('1');    //测试suiteID值为1
r($testsuite->getByIdTest($suiteID))             && p('id;deleted') && e('1;1');  //检查id为1的套件是否存在2
