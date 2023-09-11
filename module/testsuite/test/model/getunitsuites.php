#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testsuite.class.php';
su('admin');

zdTable('testsuite')->config('testsuite')->gen(9);

/**

title=测试 testsuiteModel->getUnitSuites();
cid=1
pid=1

测试productID值为1,orderBy为id_desc           >> 0
测试productID值为1,orderBy为id_desc           >> 0
测试productID值为1,orderBy为id_asc            >> 0
测试productID值为1,orderBy为name_desc,id_desc >> 0
测试productID值为1,orderBy为name_asc,id_desc  >> 0

*/
$productID = array(1, 0);
$orderBy   = array('id_desc', 'id_asc', 'name_desc,id_desc', 'name_asc,id_desc');

$testsuite = new testsuiteTest();

r($testsuite->getUnitSuitesTest($productID[0], $orderBy[0])) && p()                && e('0');                                    //测试productID值为1,orderBy为id_desc
r($testsuite->getUnitSuitesTest($productID[1], $orderBy[0])) && p('9:name;3:name') && e('这是测试套件名称9;这是测试套件名称3');  //测试productID值为1,orderBy为id_desc
r($testsuite->getUnitSuitesTest($productID[1], $orderBy[1])) && p('3:name;9:name') && e('这是测试套件名称3;这是测试套件名称9');  //测试productID值为1,orderBy为id_asc
r($testsuite->getUnitSuitesTest($productID[1], $orderBy[2])) && p('9:name;3:name') && e('这是测试套件名称9;这是测试套件名称3');  //测试productID值为1,orderBy为name_desc,id_desc
r($testsuite->getUnitSuitesTest($productID[1], $orderBy[3])) && p('3:name;9:name') && e('这是测试套件名称3;这是测试套件名称9');  //测试productID值为1,orderBy为name_asc,id_desc
