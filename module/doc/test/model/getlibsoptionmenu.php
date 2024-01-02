#!/usr/bin/env php
<?php
/**

title=测试 docModel->getLibsOptionMenu();
cid=1

- 测试libs为空 @0
- 获取文档库的目录属性6_0 @自定义文档库6/
- 获取接口库的目录属性1_0 @项目接口库1/
- 测试libs为空的目录数量 @0
- 获取文档库的目录数量 @7
- 获取接口库的目录数量 @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('module')->config('module')->gen(10);
zdTable('doclib')->config('doclib')->gen(30);
zdTable('user')->gen(5);
su('admin');

$libs[0]  = array();
$libs[1]  = array(6, 12, 13, 18);
$libs[2]  = range(1, 5);
$docTypes = array('doc', 'api');

$docTester = new docTest();
r($docTester->getLibsOptionMenuTest($libs[0], $docTypes[0])) && p()      && e('0');              // 测试libs为空
r($docTester->getLibsOptionMenuTest($libs[1], $docTypes[0])) && p('6_0') && e('自定义文档库6/'); // 获取文档库的目录
r($docTester->getLibsOptionMenuTest($libs[2], $docTypes[1])) && p('1_0') && e('项目接口库1/');   // 获取接口库的目录

r(count($docTester->getLibsOptionMenuTest($libs[0], $docTypes[0]))) && p() && e('0');  // 测试libs为空的目录数量
r(count($docTester->getLibsOptionMenuTest($libs[1], $docTypes[0]))) && p() && e('7');  // 获取文档库的目录数量
r(count($docTester->getLibsOptionMenuTest($libs[2], $docTypes[1]))) && p() && e('10'); // 获取接口库的目录数量
