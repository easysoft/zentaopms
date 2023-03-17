#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/caselib.class.php';
su('admin');

/**

title=测试 caselibModel->update();
cid=1
pid=1

测试更新名称为空时候返回 >> 『name』不能为空。
测试更新之后名称信息 >> 测试修改名称
测试更新之后描述信息 >> 测试修改描述

*/

$caselib       = new caselibTest();

$_POST['name'] = '';
$lib1          = $caselib->updateTest(201);

$_POST['name'] = '测试修改名称';
$_POST['desc'] = '测试修改描述';
$lib2          = $caselib->updateTest(201);
unset($_POST);

r($lib1) && p('name:0') && e('『name』不能为空。'); //测试更新名称为空时候返回
r($lib2) && p('name')   && e('测试修改名称');       //测试更新之后名称信息
r($lib2) && p('desc')   && e('测试修改描述');       //测试更新之后描述信息

