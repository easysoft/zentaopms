#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testsuite.class.php';
su('admin');

/**

title=测试 testsuiteModel->getUnitSuites();
cid=1
pid=1

测试productID值为1,orderBy为id_desc >> 0
测试productID值为1,orderBy为id_asc >> 0
测试productID值为1,orderBy为name_desc,id_desc >> 0
测试productID值为1,orderBy为name_asc,id_desc >> 0
测试productID值为1,orderBy为id_desc >> 0
测试productID值为1,orderBy为id_asc >> 0
测试productID值为1,orderBy为name_desc,id_desc >> 0
测试productID值为1,orderBy为name_asc,id_desc >> 0

*/
$productID = array(1, 0);
$orderBy   = array('id_desc', 'id_asc', 'name_desc,id_desc', 'name_asc,id_desc');

$testsuite = new testsuiteTest();

r($testsuite->getUnitSuitesTest($productID[0], $orderBy[0])) && p() && e('0');  //测试productID值为1,orderBy为id_desc
r($testsuite->getUnitSuitesTest($productID[0], $orderBy[1])) && p() && e('0');  //测试productID值为1,orderBy为id_asc
r($testsuite->getUnitSuitesTest($productID[0], $orderBy[2])) && p() && e('0');  //测试productID值为1,orderBy为name_desc,id_desc
r($testsuite->getUnitSuitesTest($productID[0], $orderBy[3])) && p() && e('0');  //测试productID值为1,orderBy为name_asc,id_desc
r($testsuite->getUnitSuitesTest($productID[1], $orderBy[0])) && p() && e('0');  //测试productID值为1,orderBy为id_desc
r($testsuite->getUnitSuitesTest($productID[1], $orderBy[1])) && p() && e('0');  //测试productID值为1,orderBy为id_asc
r($testsuite->getUnitSuitesTest($productID[1], $orderBy[2])) && p() && e('0');  //测试productID值为1,orderBy为name_desc,id_desc
r($testsuite->getUnitSuitesTest($productID[1], $orderBy[3])) && p() && e('0');  //测试productID值为1,orderBy为name_asc,id_desc