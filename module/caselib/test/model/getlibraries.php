#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/caselib.class.php';

zdTable('testsuite')->gen(505);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 caselibModel->getLibraries();
cid=1
pid=1

*/

$caselib = new caselibTest();
$list    = $caselib->getLibrariesTest();

r($list)        && p('201') && e('这是测试套件名称201'); // 测试获取用例库的键值对 201
r($list)        && p('402') && e('这是测试套件名称402'); // 测试获取用例库的键值对 402
r(count($list)) && p()      && e('2');                   // 测试获取用例库的键值对的数量
