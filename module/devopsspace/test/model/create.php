#!/usr/bin/env php
<?php

/**
title=测试 devopsspaceModel::getList();
timeout=0
cid=0

- 查询空间列表
 - 第1条的id属性 @1
 - 第1条的name属性 @space1
 - 第2条的id属性 @2
 - 第2条的name属性 @space2
- 查询空间总数 @10
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zendata('user')->gen(10);
zendata('ops_space')->gen(0);
zendata('ops_spaceuser')->gen(0);

su('admin');

global $tester;
$devopsspaceModel = $tester->loadModel('devopsspace');

$space = new stdClass();
$space->name  = 'space1';
$space->owner = 'test1';

r($devopsspaceModel->create($space)) && p()         && e('1'); //正常创建
r($devopsspaceModel->create($space)) && p()         && e('0'); //空间名重复
r($tester->dao->getError())          && p('name:0') && e('『name』已经有『space1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。');//空间名重复信息
$space->name = '';
r($devopsspaceModel->create($space)) && p()         && e('0'); //空间名为空
r($tester->dao->getError())          && p('name:0') && e('『name』不能为空。');//空间名为空信息
$space->name  = 'test2';
$space->owner = '';
r($devopsspaceModel->create($space)) && p()          && e('0');//owner为空
r($tester->dao->getError())          && p('owner:0') && e('『owner』不能为空。');//owner为空信息
