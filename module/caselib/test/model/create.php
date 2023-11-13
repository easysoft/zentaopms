#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/caselib.class.php';

zdTable('testsuite')->gen(0);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 caselibModel->create();
cid=1
pid=1

*/

$caselib = new caselibTest();

$lib_noname = array('name' => '');
$lib_normal = array('name' => 'lib name', 'desc' => 'lib desc');
$lib_repeat = array('name' => 'lib name');

r($caselib->createTest($lib_noname)) && p('name:0') && e('『名称』不能为空。'); // 测试名称是空时候添加
r($caselib->createTest($lib_normal)) && p('name')   && e('lib name');           // 测试添加的名称信息
r($caselib->createTest($lib_repeat)) && p('name:0') && e('『名称』已经有『lib name』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 测试名称是空时候添加
