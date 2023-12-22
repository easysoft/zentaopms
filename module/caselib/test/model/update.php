#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/caselib.class.php';

zdTable('testsuite')->gen(10);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 caselibModel->update();
timeout=0
cid=1

- 测试更新名称为空时候返回第name条的0属性 @『name』不能为空。
- 测试更新之后名称信息
 - 属性name @测试修改名称
 - 属性desc @测试修改描述

*/

$lib1 = new stdclass();
$lib1->id   = 1;
$lib1->name = '';
$lib1->uid  = '';

$lib2 = new stdclass();
$lib2->id   = 2;
$lib2->name = '测试修改名称';
$lib2->desc = '测试修改描述';
$lib2->uid  = '';

$caselib = new caselibTest();
r($caselib->updateTest($lib1)) && p('name:0')    && e('『名称』不能为空。');        //测试更新名称为空时候返回
r($caselib->updateTest($lib2)) && p('name;desc') && e('测试修改名称;测试修改描述'); //测试更新之后名称信息
