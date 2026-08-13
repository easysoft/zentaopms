#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getSystemBySpace();
timeout=0
cid=16031

- 查询无效的空间 @0
- 查询空间1下的应用数量 @2
- 查询空间1下的第1个应用名称 @system-one
- 查询空间2下应用关联的产品 @3
- 查询空间1下的第2个应用名称 @system-two

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);

$repo = zenData('ops_repo');
$repo->id->range('1-3');
$repo->spaceID->range('1,1,2');
$repo->product->range('1,2,3');
$repo->name->range('repo-one,repo-two,repo-three');
$repo->gitUID->range('system-repo-gituid-1,system-repo-gituid-2,system-repo-gituid-3');
$repo->status->range('active{3}');
$repo->deleted->range('0{3}');
$repo->gen(3);

$system = zenData('system');
$system->id->range('1-3');
$system->product->range('1,2,3');
$system->name->range('system-one,system-two,system-three');
$system->deleted->range('0{3}');
$system->gen(3);

su('admin');

$spaceTester = new spaceModelTest();

r($spaceTester->getSystemBySpaceCountTest(0))             && p() && e('0');            // 查询无效的空间
r($spaceTester->getSystemBySpaceCountTest(1))             && p() && e('2');            // 查询空间1下的应用数量
r($spaceTester->getSystemBySpaceFieldTest(1, 1, 'name'))    && p() && e('system-one');   // 查询空间1下的第1个应用名称
r($spaceTester->getSystemBySpaceFieldTest(2, 3, 'product')) && p() && e('3');            // 查询空间2下应用关联的产品
r($spaceTester->getSystemBySpaceFieldTest(1, 2, 'name'))    && p() && e('system-two');   // 查询空间1下的第2个应用名称
