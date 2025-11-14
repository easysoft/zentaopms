#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

zenData('testsuite')->gen(10);
zenData('user')->gen(1);

su('admin');

/**

title=测试 caselibModel->update();
timeout=0
cid=15537

- 测试更新名称为空时候返回第name条的0属性 @『名称』不能为空。
- 测试更新之后名称信息属性name @测试修改名称
- 测试更新之后描述信息属性desc @测试修改描述
- 测试权限修改为公开属性type @public
- 测试清空描述属性desc @~~

*/

$lib1 = new stdclass();
$lib1->id   = 1;
$lib1->name = '';
$lib1->uid  = '';

$lib2 = new stdclass();
$lib2->id   = 2;
$lib2->name = '测试修改名称';
$lib2->uid  = '';

$lib3 = new stdclass();
$lib3->id   = 3;
$lib3->desc = '测试修改描述';
$lib3->uid  = '';

$lib4 = new stdclass();
$lib4->id   = 4;
$lib4->type = 'public';
$lib4->uid  = '';

$lib5 = new stdclass();
$lib5->id   = 5;
$lib5->desc = '';
$lib5->uid  = '';

$caselib = new caselibTest();
r($caselib->updateTest($lib1)) && p('name:0') && e('『名称』不能为空。'); //测试更新名称为空时候返回
r($caselib->updateTest($lib2)) && p('name')   && e('测试修改名称');       //测试更新之后名称信息
r($caselib->updateTest($lib3)) && p('desc')   && e('测试修改描述');       //测试更新之后描述信息
r($caselib->updateTest($lib4)) && p('type')   && e('public');             //测试权限修改为公开
r($caselib->updateTest($lib5)) && p('desc')   && e('~~');                 //测试清空描述
