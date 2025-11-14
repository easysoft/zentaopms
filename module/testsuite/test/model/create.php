#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testsuite.unittest.class.php';
zenData('testsuite')->gen(1);

/**

title=测试 testsuiteModel->create();
cid=19138
pid=1

*/
$productID = array(1, 0);
$name      = array('这是测试套件名称1000', '');
$type      = array('private', 'public', '');

$testsuite = new testsuiteTest();

r($testsuite->createTest($productID[0], $name[0], $type[0])) && p() && e('2');                         //测试productID为1,name正常存在,type为private
r($testsuite->createTest($productID[0], $name[1], $type[0])) && p('name:0') && e('『套件名称』不能为空。');  //测试productID为1,name为空,type为private
r($testsuite->createTest($productID[0], $name[0], $type[1])) && p() && e('3');                         //测试productID为1,name正常存在,type为public
r($testsuite->createTest($productID[0], $name[1], $type[1])) && p('name:0') && e('『套件名称』不能为空。');  //测试productID为1,name为空,type为public
r($testsuite->createTest($productID[0], $name[0], $type[2])) && p() && e('4');                         //测试productID为1,name正常存在,type为空
r($testsuite->createTest($productID[0], $name[1], $type[2])) && p('name:0') && e('『套件名称』不能为空。');  //测试productID为1,name为空,type为空
r($testsuite->createTest($productID[1], $name[0], $type[0])) && p() && e('5');                         //测试productID为0,name正常存在,type为private
r($testsuite->createTest($productID[1], $name[1], $type[0])) && p('name:0') && e('『套件名称』不能为空。');  //测试productID为0,name为空,type为private
r($testsuite->createTest($productID[1], $name[0], $type[1])) && p() && e('6');                         //测试productID为0,name正常存在,type为public
r($testsuite->createTest($productID[1], $name[1], $type[1])) && p('name:0') && e('『套件名称』不能为空。');  //测试productID为0,name为空,type为public
r($testsuite->createTest($productID[1], $name[0], $type[2])) && p() && e('7');                         //测试productID为0,name正常存在,type为空
r($testsuite->createTest($productID[1], $name[1], $type[2])) && p('name:0') && e('『套件名称』不能为空。');  //测试productID为0,name为空,type为空
