#!/usr/bin/env php
<?php

/**
title=测试 spaceModel::getList();
timeout=0
cid=16022

- 正常创建 @1
- 空间名重复 @0
- 空间名重复信息第name条的0属性 @『name』已经有『space1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
- 空间名为空 @0
- 空间名为空信息第name条的0属性 @『name』不能为空。
- owner为空 @0
- owner为空信息第owner条的0属性 @『owner』不能为空。
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zendata('user')->gen(10);
zendata('ops_space')->gen(0);
zendata('ops_spaceuser')->gen(0);

su('admin');

global $tester;
$spaceModel = $tester->loadModel('space');

$space = new stdClass();
$space->name  = 'space1';
$space->owner = 'test1';

r($spaceModel->create($space)) && p()         && e('1'); //正常创建
r($spaceModel->create($space)) && p()         && e('0'); //空间名重复
r($tester->dao->getError())          && p('name:0') && e('『name』已经有『space1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。');//空间名重复信息
$space->name = '';
r($spaceModel->create($space)) && p()         && e('0'); //空间名为空
r($tester->dao->getError())          && p('name:0') && e('『name』不能为空。');//空间名为空信息
$space->name  = 'test2';
$space->owner = '';
r($spaceModel->create($space)) && p()          && e('0');//owner为空
r($tester->dao->getError())          && p('owner:0') && e('『owner』不能为空。');//owner为空信息
