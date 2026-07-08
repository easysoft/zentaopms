#!/usr/bin/env php
<?php

/**
title=测试 spaceModel::getList();
timeout=0
cid=16026

- 查询空间列表
 - 第1条的id属性 @1
 - 第1条的name属性 @space1
 - 第2条的id属性 @2
 - 第2条的name属性 @space2
- 查询空间总数 @10
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zendata('user')->gen(10);
zendata('ops_space')->gen(10);
zendata('ops_spaceuser')->gen(10);

su('admin');

global $tester;
$spaceModel = $tester->loadModel('space');

r($spaceModel->getList())        && p('1:id,name;2:id,name') && e('1,space1;2,space2'); //查询空间列表
r(count($spaceModel->getList())) && p()                      && e('10');                //查询空间总数
