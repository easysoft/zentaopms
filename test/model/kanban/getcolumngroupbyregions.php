#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getColumnGroupByRegions();
cid=1
pid=1

测试获取region 1 2 3 的看板列数量 >> 12
测试获取region 4,5,6的看板列数量 >> 12
测试获取region 7,8,9的看板列数量 >> 12
测试获取region 10,11,12的看板列数量 >> 12
测试获取region 13,14,15的看板列数量 >> 12

*/

$regions1 = '1,2,3';
$regions2 = '4,5,6';
$regions3 = '7,8,9';
$regions4 = '10,11,12';
$regions5 = '13,14,15';

$kanban = new kanbanTest();

r($kanban->getColumnGroupByRegionsTest($regions1)) && p() && e('12'); // 测试获取region 1 2 3 的看板列数量
r($kanban->getColumnGroupByRegionsTest($regions2)) && p() && e('12'); // 测试获取region 4,5,6的看板列数量
r($kanban->getColumnGroupByRegionsTest($regions3)) && p() && e('12'); // 测试获取region 7,8,9的看板列数量
r($kanban->getColumnGroupByRegionsTest($regions4)) && p() && e('12'); // 测试获取region 10,11,12的看板列数量
r($kanban->getColumnGroupByRegionsTest($regions5)) && p() && e('12'); // 测试获取region 13,14,15的看板列数量