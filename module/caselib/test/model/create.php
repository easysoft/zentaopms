#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('testsuite')->gen(0);
zenData('user')->gen(1);

su('admin');

/**

title=测试 caselibModel->create();
cid=15526

- 测试名称是空时候添加第name条的0属性 @『名称』不能为空。
- 测试添加的名称信息属性name @lib name
- 测试名称是空时候添加第name条的0属性 @『名称』已经有『lib name』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
- 测试添加的名称信息 2属性name @lib name 2
- 测试添加的名称信息 3属性name @lib name 3

*/

$caselib = new caselibModelTest();

$lib_noname  = array('name' => '');
$lib_normal  = array('name' => 'lib name', 'desc' => 'lib desc');
$lib_repeat  = array('name' => 'lib name');
$lib_normal2 = array('name' => 'lib name 2', 'desc' => 'lib desc 2');
$lib_normal3 = array('name' => 'lib name 3', 'desc' => 'lib desc 3');

r($caselib->createTest($lib_noname))  && p('name:0') && e('『名称』不能为空。'); // 测试名称是空时候添加
r($caselib->createTest($lib_normal))  && p('name')   && e('lib name');           // 测试添加的名称信息
r($caselib->createTest($lib_repeat))  && p('name:0') && e('『名称』已经有『lib name』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 测试名称是空时候添加
r($caselib->createTest($lib_normal2)) && p('name')   && e('lib name 2');           // 测试添加的名称信息 2
r($caselib->createTest($lib_normal3)) && p('name')   && e('lib name 3');           // 测试添加的名称信息 3
