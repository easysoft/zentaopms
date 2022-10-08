#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/caselib.class.php';
su('admin');

/**

title=测试 caselibModel->getById();
cid=1
pid=1

测试获取数据的名称信息 >> 这是测试套件名称201
测试获取数据的描述信息 >> 这是测试套件的描述201
测试获取数据的type类型 >> library

*/

$caselib = new caselibTest();
$data    = $caselib->getByIdTest(201);

r($data) && p('name') && e('这是测试套件名称201');   //测试获取数据的名称信息
r($data) && p('desc') && e('这是测试套件的描述201'); //测试获取数据的描述信息
r($data) && p('type') && e('library');               //测试获取数据的type类型