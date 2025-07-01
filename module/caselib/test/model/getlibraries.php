#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

zenData('testsuite')->gen(505);
zenData('user')->gen(1);

su('admin');

/**

title=测试 caselibModel->getLibraries();
timeout=0
cid=1

- 测试获取用例库的键值对 201属性201 @这是测试套件名称201
- 测试获取用例库的键值对 402属性402 @这是测试套件名称402
- 测试获取用例库的键值对的数量 @2

*/

$caselib = new caselibTest();
$list    = $caselib->getLibrariesTest();

r($list)        && p('201') && e('这是测试套件名称201'); // 测试获取用例库的键值对 201
r($list)        && p('402') && e('这是测试套件名称402'); // 测试获取用例库的键值对 402
r(count($list)) && p()      && e('2');                   // 测试获取用例库的键值对的数量