#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/caselib.class.php';
su('admin');

/**

title=测试 caselibModel->getList();
cid=1
pid=1

测试获取列表的个数，目前只有一个 >> 1
测试获取列表某个用例库的名称信息 >> 这是测试套件名称201

*/

$caselib = new caselibTest();
$list    = $caselib->getListTest();

r(count($list)) && p()           && e('1');                  //测试获取列表的个数，目前只有一个
r($list)        && p('201:name') && e('这是测试套件名称201');//测试获取列表某个用例库的名称信息