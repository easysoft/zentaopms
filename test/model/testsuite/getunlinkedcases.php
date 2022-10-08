#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testsuite.class.php';
su('admin');

/**

title=测试 testsuiteModel->getUnlinkedCases();
cid=1
pid=1

测试suiteID值为1,param值为0 >> 4,这个是测试用例4;3,这个是测试用例3
测试suiteID值为1,param值为myQueryID >> 4,这个是测试用例4;3,这个是测试用例3
测试suiteID值为2,param值为0 >> 2,这个是测试用例2;1,这个是测试用例1
测试suiteID值为2,param值为myQueryID >> 2,这个是测试用例2;1,这个是测试用例1

*/
$suiteID = array(1, 2);
$param = array(0, 'myQueryID');

$testsuite = new testsuiteTest();

r($testsuite->getUnlinkedCasesTest($suiteID[0], $param[0])) && p('0:id,title;1:id,title') && e('4,这个是测试用例4;3,这个是测试用例3');  //测试suiteID值为1,param值为0
r($testsuite->getUnlinkedCasesTest($suiteID[0], $param[1])) && p('0:id,title;1:id,title') && e('4,这个是测试用例4;3,这个是测试用例3');  //测试suiteID值为1,param值为myQueryID
r($testsuite->getUnlinkedCasesTest($suiteID[1], $param[0])) && p('0:id,title;1:id,title') && e('2,这个是测试用例2;1,这个是测试用例1');  //测试suiteID值为2,param值为0
r($testsuite->getUnlinkedCasesTest($suiteID[1], $param[1])) && p('0:id,title;1:id,title') && e('2,这个是测试用例2;1,这个是测试用例1');  //测试suiteID值为2,param值为myQueryID