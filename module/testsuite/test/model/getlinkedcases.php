#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testsuite.class.php';
su('admin');

zdTable('case')->gen(2);
zdTable('testsuite')->gen(1);
zdTable('suitecase')->gen(2);
zdTable('testresult')->gen(2);

/**

title=测试 testsuiteModel->getLinkedCases();
cid=1
pid=1

测试suiteID值正常存在,orderBy值为id_desc,append值为true             >> 2,这个是测试用例2,1;1,这个是测试用例1,1
测试suiteID值正常存在,orderBy值为id_desc,append值为false            >> 2,这个是测试用例2,a;1,这个是测试用例1,a
测试suiteID值正常存在,orderBy值为id_asc,append值为true              >> 1,这个是测试用例1,2;2,这个是测试用例2,1
测试suiteID值正常存在,orderBy值为id_asc,append值为false             >> 1,这个是测试用例1,a;2,这个是测试用例2,a
测试suiteID值正常存在,orderBy值为title_desc,id_desc,append值为true  >> 2,这个是测试用例2,1;1,这个是测试用例1,1
测试suiteID值正常存在,orderBy值为title_desc,id_desc,append值为false >> 2,这个是测试用例2,a;1,这个是测试用例1,a
测试suiteID值正常存在,orderBy值为title_asc,id_desc,append值为true   >> 1,这个是测试用例1,1;2,这个是测试用例2,1
测试suiteID值正常存在,orderBy值为title_asc,id_desc,append值为false  >> 1,这个是测试用例1,a;2,这个是测试用例2,a

 */

$suiteID = 1;
$orderBy = array('id_desc', 'id_asc', 'title_desc,id_desc', 'title_asc,id_desc');
$append  = array(true, false);

$testsuite = new testsuiteTest();

r($testsuite->getLinkedCasesTest($suiteID, $orderBy[0], null, $append[0])) && p('2:id,title,results;1:id,title,results') && e('2,这个是测试用例2,1;1,这个是测试用例1,1');  //测试suiteID值正常存在,orderBy值为id_desc,append值为true
r($testsuite->getLinkedCasesTest($suiteID, $orderBy[0], null, $append[1])) && p('2:id,title,results;1:id,title,results') && e('2,这个是测试用例2,a;1,这个是测试用例1,a');  //测试suiteID值正常存在,orderBy值为id_desc,append值为false
r($testsuite->getLinkedCasesTest($suiteID, $orderBy[1], null, $append[0])) && p('1:id,title,results;2:id,title,results') && e('1,这个是测试用例1,1;2,这个是测试用例2,1');  //测试suiteID值正常存在,orderBy值为id_asc,append值为true
r($testsuite->getLinkedCasesTest($suiteID, $orderBy[1], null, $append[1])) && p('1:id,title,results;2:id,title,results') && e('1,这个是测试用例1,a;2,这个是测试用例2,a');  //测试suiteID值正常存在,orderBy值为id_asc,append值为false
r($testsuite->getLinkedCasesTest($suiteID, $orderBy[2], null, $append[0])) && p('2:id,title,results;1:id,title,results') && e('2,这个是测试用例2,1;1,这个是测试用例1,1');  //测试suiteID值正常存在,orderBy值为title_desc,id_desc,append值为true
r($testsuite->getLinkedCasesTest($suiteID, $orderBy[2], null, $append[1])) && p('2:id,title,results;1:id,title,results') && e('2,这个是测试用例2,a;1,这个是测试用例1,a');  //测试suiteID值正常存在,orderBy值为title_desc,id_desc,append值为false
r($testsuite->getLinkedCasesTest($suiteID, $orderBy[3], null, $append[0])) && p('1:id,title,results;2:id,title,results') && e('1,这个是测试用例1,1;2,这个是测试用例2,1');  //测试suiteID值正常存在,orderBy值为title_asc,id_desc,append值为true
r($testsuite->getLinkedCasesTest($suiteID, $orderBy[3], null, $append[1])) && p('1:id,title,results;2:id,title,results') && e('1,这个是测试用例1,a;2,这个是测试用例2,a');  //测试suiteID值正常存在,orderBy值为title_asc,id_desc,append值为false
