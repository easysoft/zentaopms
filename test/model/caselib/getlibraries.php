#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/caselib.class.php';
su('admin');

/**

title=测试 caselibModel->getLibraries();
cid=1
pid=1

测试获取用例库的键值对 >> 这是测试套件名称201

*/

$caselib = new caselibTest();
$list    = $caselib->getLibrariesTest();

r($list) && p('201') && e('这是测试套件名称201'); //测试获取用例库的键值对