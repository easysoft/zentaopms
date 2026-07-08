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
include dirname(__FILE__, 2) . '/lib/model.class.php';

zendata('user')->gen(10);
zendata('ops_space')->gen(10);
zendata('ops_spaceuser')->gen(10);

su('admin');

$spaceTester = new spaceModelTest();
r($spaceTester->getByIDTest($idList[0])) && p()                             && e('0');                            // 获取ID=0的空间
r($spaceTester->getByIDTest($idList[1])) && p('name,k8space,owner,default') && e('空间1,quickon-system,admin,0'); // 获取ID=1的空间
r($spaceTester->getByIDTest($idList[2])) && p()                             && e('0');                            // 获取ID=6的空间
