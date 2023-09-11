#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testsuite.class.php';
su('admin');

zdTable('case')->gen(2);
zdTable('testsuite')->gen(1);
zdTable('suitecase')->gen(2);

/**

title=测试 testsuiteModel->getLinkedCasePairs();
cid=1
pid=1

测试suiteID值正常存在 >> 0
测试suiteID值正常存在 >> 这个是测试用例2;这个是测试用例1
测试suiteID值正常存在 >> 0

*/
$suiteID = array(0, 1);

$testsuite = new testsuiteTest();

r($testsuite->getLinkedCasePairsTest($suiteID[0])) && p('1')   && e('0');                                //测试suiteID值正常存在,orderBy值为id_desc,append值为true
r($testsuite->getLinkedCasePairsTest($suiteID[1])) && p('2;1') && e('这个是测试用例2;这个是测试用例1');  //测试suiteID值正常存在,orderBy值为id_desc,append值为false
