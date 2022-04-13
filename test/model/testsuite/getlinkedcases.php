#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testsuite.class.php';
su('admin');

/**

title=测试 testsuiteModel->getLinkedCases();
cid=1
pid=1

测试suiteID值正常存在,orderBy值为id_desc,append值为true >> 2,这个是测试用例2;1,这个是测试用例1
测试suiteID值正常存在,orderBy值为id_desc,append值为false >> 2,这个是测试用例2;1,这个是测试用例1
测试suiteID值正常存在,orderBy值为id_asc,append值为true >> 1,这个是测试用例1;2,这个是测试用例2
测试suiteID值正常存在,orderBy值为id_asc,append值为false >> 1,这个是测试用例1;2,这个是测试用例2
测试suiteID值正常存在,orderBy值为title_desc,id_desc,append值为true >> 2,这个是测试用例2;1,这个是测试用例1
测试suiteID值正常存在,orderBy值为title_desc,id_desc,append值为false >> 2,这个是测试用例2;1,这个是测试用例1
测试suiteID值正常存在,orderBy值为title_asc,id_desc,append值为true >> 1,这个是测试用例1;2,这个是测试用例2
测试suiteID值正常存在,orderBy值为title_asc,id_desc,append值为false >> 1,这个是测试用例1;2,这个是测试用例2

*/
$suiteID = 1;
$orderBy = array('id_desc', 'id_asc', 'title_desc,id_desc', 'title_asc,id_desc');
$append  = array(true, false);

$testsuite = new testsuiteTest();

r($testsuite->getLinkedCasesTest($suiteID, $orderBy[0], '', $append[0])) && p('2:id,title;1:id,title') && e('2,这个是测试用例2;1,这个是测试用例1');  //测试suiteID值正常存在,orderBy值为id_desc,append值为true
r($testsuite->getLinkedCasesTest($suiteID, $orderBy[0], '', $append[1])) && p('2:id,title;1:id,title') && e('2,这个是测试用例2;1,这个是测试用例1');  //测试suiteID值正常存在,orderBy值为id_desc,append值为false
r($testsuite->getLinkedCasesTest($suiteID, $orderBy[1], '', $append[0])) && p('1:id,title;2:id,title') && e('1,这个是测试用例1;2,这个是测试用例2');  //测试suiteID值正常存在,orderBy值为id_asc,append值为true
r($testsuite->getLinkedCasesTest($suiteID, $orderBy[1], '', $append[1])) && p('1:id,title;2:id,title') && e('1,这个是测试用例1;2,这个是测试用例2');  //测试suiteID值正常存在,orderBy值为id_asc,append值为false
r($testsuite->getLinkedCasesTest($suiteID, $orderBy[2], '', $append[0])) && p('2:id,title;1:id,title') && e('2,这个是测试用例2;1,这个是测试用例1');  //测试suiteID值正常存在,orderBy值为title_desc,id_desc,append值为true
r($testsuite->getLinkedCasesTest($suiteID, $orderBy[2], '', $append[1])) && p('2:id,title;1:id,title') && e('2,这个是测试用例2;1,这个是测试用例1');  //测试suiteID值正常存在,orderBy值为title_desc,id_desc,append值为false
r($testsuite->getLinkedCasesTest($suiteID, $orderBy[3], '', $append[0])) && p('1:id,title;2:id,title') && e('1,这个是测试用例1;2,这个是测试用例2');  //测试suiteID值正常存在,orderBy值为title_asc,id_desc,append值为true
r($testsuite->getLinkedCasesTest($suiteID, $orderBy[3], '', $append[1])) && p('1:id,title;2:id,title') && e('1,这个是测试用例1;2,这个是测试用例2');  //测试suiteID值正常存在,orderBy值为title_asc,id_desc,append值为false