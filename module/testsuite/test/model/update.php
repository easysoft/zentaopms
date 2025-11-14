#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testsuite.unittest.class.php';
su('admin');

zenData('testsuite')->gen(1);
zenData('file')->gen(1);

/**

title=测试 testsuiteModel->update();
cid=19150
pid=1

测试productID为1,name正常存在,type为privatedesc为空,     >> type,public,private
测试productID为1,name为空,type为private,desc为空         >> 『套件名称』不能为空。
测试productID为1,name正常存在,type为public,desc为空      >> type,private,public
测试productID为1,name为空,type为public,desc为空          >> 『套件名称』不能为空。
测试productID为1,name正常存在,type为空,desc为空          >> type,public,
测试productID为1,name为空,type为空,desc为空              >> 『套件名称』不能为空。
测试productID为0,name正常存在,type为private,desc为空     >> 0
测试productID为0,name正常存在,type为private,desc为默认值 >> desc,1

 */

$productID = array(1, 0);
$name      = array('这是测试套件名称1000', '');
$type      = array('private', 'public', '');
$uid       = array('', uniqid());
$desc      = '<img src="/index.php?m=file&amp;f=read&amp;t=jpeg&amp;fileID=24">';

$testsuite = new testsuiteTest();

r($testsuite->updateTest($productID[0], $name[0], $type[0], '',    $uid[0])) && p('2:field,old,new') && e('type,public,private');     //测试productID为1,name正常存在,type为private
r($testsuite->updateTest($productID[0], $name[1], $type[0], '',    $uid[0])) && p('name:0')          && e('『套件名称』不能为空。');  //测试productID为1,name为空,type为private
r($testsuite->updateTest($productID[0], $name[0], $type[1], '',    $uid[0])) && p('0:field,old,new') && e('type,private,public');     //测试productID为1,name正常存在,type为public
r($testsuite->updateTest($productID[0], $name[1], $type[1], '',    $uid[0])) && p('name:0')          && e('『套件名称』不能为空。');  //测试productID为1,name为空,type为public
r($testsuite->updateTest($productID[0], $name[0], $type[2], '',    $uid[0])) && p('0:field,old,new') && e('type,public,~~');          //测试productID为1,name正常存在,type为空
r($testsuite->updateTest($productID[0], $name[1], $type[2], '',    $uid[0])) && p('name:0')          && e('『套件名称』不能为空。');  //测试productID为1,name为空,type为空
r($testsuite->updateTest($productID[1], $name[0], $type[0], '',    $uid[0])) && p()                  && e('0');                       //测试productID为0,name正常存在,type为private
r($testsuite->updateTest($productID[0], $name[0], $type[0], $desc, $uid[1])) && p('0:field,result')  && e('desc,1');                  //测试productID为0,name正常存在,type为private
