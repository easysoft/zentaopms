#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testsuite.class.php';
su('admin');

/**

title=测试 testsuiteModel->create();
cid=1
pid=1

测试productID为1,name正常存在,type为private >> 202
测试productID为1,name为空,type为private >> 『名称』不能为空。
测试productID为1,name正常存在,type为public >> 203
测试productID为1,name为空,type为public >> 『名称』不能为空。
测试productID为1,name正常存在,type为空 >> 204
测试productID为1,name为空,type为空 >> 『名称』不能为空。
测试productID为0,name正常存在,type为private >> 205
测试productID为0,name为空,type为private >> 『名称』不能为空。
测试productID为0,name正常存在,type为public >> 206
测试productID为0,name为空,type为public >> 『名称』不能为空。
测试productID为0,name正常存在,type为空 >> 207
测试productID为0,name为空,type为空 >> 『名称』不能为空。

*/
$productID = array(1, 0);
$name      = array('这是测试套件名称1000', '');
$type      = array('private', 'public', '');

$testsuite = new testsuiteTest();

r($testsuite->createTest($productID[0], $name[0], $type[0])) && p() && e('202');                         //测试productID为1,name正常存在,type为private
r($testsuite->createTest($productID[0], $name[1], $type[0])) && p('name:0') && e('『名称』不能为空。');  //测试productID为1,name为空,type为private
r($testsuite->createTest($productID[0], $name[0], $type[1])) && p() && e('203');                         //测试productID为1,name正常存在,type为public
r($testsuite->createTest($productID[0], $name[1], $type[1])) && p('name:0') && e('『名称』不能为空。');  //测试productID为1,name为空,type为public
r($testsuite->createTest($productID[0], $name[0], $type[2])) && p() && e('204');                         //测试productID为1,name正常存在,type为空
r($testsuite->createTest($productID[0], $name[1], $type[2])) && p('name:0') && e('『名称』不能为空。');  //测试productID为1,name为空,type为空
r($testsuite->createTest($productID[1], $name[0], $type[0])) && p() && e('205');                         //测试productID为0,name正常存在,type为private
r($testsuite->createTest($productID[1], $name[1], $type[0])) && p('name:0') && e('『名称』不能为空。');  //测试productID为0,name为空,type为private
r($testsuite->createTest($productID[1], $name[0], $type[1])) && p() && e('206');                         //测试productID为0,name正常存在,type为public
r($testsuite->createTest($productID[1], $name[1], $type[1])) && p('name:0') && e('『名称』不能为空。');  //测试productID为0,name为空,type为public
r($testsuite->createTest($productID[1], $name[0], $type[2])) && p() && e('207');                         //测试productID为0,name正常存在,type为空
r($testsuite->createTest($productID[1], $name[1], $type[2])) && p('name:0') && e('『名称』不能为空。');  //测试productID为0,name为空,type为空
