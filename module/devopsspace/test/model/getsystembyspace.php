#!/usr/bin/env php
<?php

/**
title=测试 devopsspaceModel::getSystemBySpace();
timeout=0
cid=0

- 查询无效的空间 @0
- 查询空间1下的应用列表
 - 第1条的id属性 @1
 - 第1条的name属性 @应用1
- 查询空间2下的应用列表
 - 第1条的id属性 @1
 - 第1条的product属性 @1
- 查询空间1下的应用总数 @1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zendata('user')->gen(10);
zendata('repo')->gen(10);
zendata('system')->gen(10);
zendata('ops_space')->gen(10);
zendata('ops_spaceuser')->gen(10);

su('admin');

global $tester;
$devopsspaceModel = $tester->loadModel('devopsspace');

r($devopsspaceModel->getSystemBySpace(0))        && p()                 && e('0'); //查询无效的空间
r($devopsspaceModel->getSystemBySpace(1))        && p('1:id;1:name')    && e('1,应用1'); //查询空间1下的应用列表
r($devopsspaceModel->getSystemBySpace(2))        && p('1:id;1:product') && e('1,1'); //查询空间2下的应用列表
r(count($devopsspaceModel->getSystemBySpace(1))) && p()                 && e('1'); //查询空间1下的应用总数
