#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->splitColumn();
cid=1
pid=1

测试拆分列1 >> 2
测试拆分列2 >> 2
测试拆分列3 >> 2
测试拆分列4 >> 在制品数量必须是正整数。
测试拆分列4 >> 『看板列名称』不能为空。

*/

$columnIDList = array('1', '2', '3', '4');

$name     = array('1' => '拆分的子列1', '2' => '拆分的子列2');
$color    = array('1' => '#333', '2' => '#333');
$WIPCount = array('1' => '111');
$noLimit  = array('2' => '-1');
$splits1  = array('name' => $name, 'color' => $color, 'WIPCount' => $WIPCount, 'noLimit' => $noLimit);

$name     = array('1' => '拆分的子列3', '2' => '拆分的子列4');
$WIPCount = array('1' => '111', '2' => '111');
$splits2  = array('name' => $name, 'color' => $color, 'WIPCount' => $WIPCount);

$name    = array('1' => '拆分的子列5', '2' => '拆分的子列6');
$noLimit = array('1' => '-1', '2' => '-1');
$splits3 = array('name' => $name, 'color' => $color, 'noLimit' => $noLimit);

$WIPCount = array('1' => 'a', '2' => '0');
$splits4  = array('name' => $name, 'color' => $color, 'WIPCount' => $WIPCount);

$name     = array('1' => '', '2' => '');
$splits5  = array('name' => $name, 'color' => $color, 'noLimit' => $noLimit);

$kanban = new kanbanTest();

r($kanban->splitColumnTest($columnIDList[0], $splits1)) && p()        && e('2');                        // 测试拆分列1
r($kanban->splitColumnTest($columnIDList[1], $splits2)) && p()        && e('2');                        // 测试拆分列2
r($kanban->splitColumnTest($columnIDList[2], $splits3)) && p()        && e('2');                        // 测试拆分列3
r($kanban->splitColumnTest($columnIDList[3], $splits4)) && p('limit') && e('在制品数量必须是正整数。'); // 测试拆分列4
r($kanban->splitColumnTest($columnIDList[3], $splits5)) && p('name')  && e('『看板列名称』不能为空。'); // 测试拆分列4
