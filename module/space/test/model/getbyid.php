#!/usr/bin/env php
<?php

/**
title=测试 spaceModel::getList();
timeout=0
cid=16025

- 查询ID为1的空间名称
 - 属性id @1
 - 属性name @space1
- 查询ID为2的空间拥有者
 - 属性id @2
 - 属性owner @test2
- 查询ID为2的空间成员第team条的0属性 @test1
- 查询不存在的空间 @0
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zendata('user')->gen(10);
zendata('ops_space')->gen(10);
zendata('ops_spaceuser')->gen(10);

su('admin');

global $tester;
$spaceModel = $tester->loadModel('space');

r($spaceModel->getByID(1)) && p('id,name')  && e('1,space1'); //查询ID为1的空间名称
r($spaceModel->getByID(2)) && p('id,owner') && e('2,test2');  //查询ID为2的空间拥有者
r($spaceModel->getByID(2)) && p('team:0')   && e('test1');    //查询ID为2的空间成员
r($spaceModel->getByID(0)) && p()           && e('0');        //查询不存在的空间
