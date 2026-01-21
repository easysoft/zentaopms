#!/usr/bin/env php
<?php
/**
title=测试 spaceModel::deleteSpace();
timeout=0
cid=16023

- 删除不存在的空间 @1
- 删除存在的空间 @1
- 查询删除的空间
 - 属性id @1
 - 属性name @space1
 - 属性deleted @1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zendata('user')->gen(10);
zendata('ops_space')->gen(10);
zendata('ops_spaceuser')->gen(10);

su('admin');

global $tester;
$spaceModel = $tester->loadModel('space');

r($spaceModel->deleteSpace(0))  && p() && e('1'); //删除不存在的空间
r($spaceModel->deleteSpace(1))  && p() && e('1'); //删除存在的空间
r($spaceModel->getById(1))      && p('id,name,deleted') && e('1,space1,1'); //查询删除的空间
