#!/usr/bin/env php
<?php
/**
title=测试 spaceModel::update();
timeout=0
cid=16032

- 正常修改
 - 第0条的old属性 @space1
 - 第0条的new属性 @update space1
- 空间名重复 @0
- 空间名重复信息第name条的0属性 @『name』已经有『update space1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
- 空间名为空 @0
- 空间名为空信息第name条的0属性 @『name』不能为空。
- owner为空 @0
- owner为空信息第owner条的0属性 @『owner』不能为空。
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zendata('user')->gen(10);
zendata('ops_space')->gen(5);
zendata('ops_spaceuser')->gen(5);

su('admin');

global $tester;
$spaceModel = $tester->loadModel('space');
$oldSpace         = $spaceModel->getById(1);

$space = new stdClass();
$space->name  = 'update space1';
$space->owner = 'test12';

r($spaceModel->update($oldSpace, $space)) && p('0:old;0:new') && e('space1,update space1'); //正常修改

$oldSpace = $spaceModel->getById(2);
r($spaceModel->update($oldSpace, $space)) && p()         && e('0'); //空间名重复
r($tester->dao->getError())                     && p('name:0') && e('『name』已经有『update space1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。');//空间名重复信息
$space->name = '';
r($spaceModel->update($oldSpace, $space)) && p()         && e('0'); //空间名为空
r($tester->dao->getError())                     && p('name:0') && e('『name』不能为空。');//空间名为空信息
$space->name  = 'test2';
$space->owner = '';
r($spaceModel->update($oldSpace, $space)) && p()          && e('0');//owner为空
r($tester->dao->getError())                     && p('owner:0') && e('『owner』不能为空。');//owner为空信息
