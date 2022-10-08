#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getColumnsByObject();
cid=1
pid=1

测试获取parent 457的看板列数量 >> 2
测试获取parent 468的看板列数量 >> 2
测试获取parent 471的看板列数量 >> 2
测试获取parent 100001的看板列数量 >> 0
测试获取region 1的看板列数量 >> 0
测试获取region 2的看板列数量 >> 0
测试获取region 3的看板列数量 >> 0
测试获取region 100001的看板列数量 >> 0
测试获取group 1的看板列数量 >> 7
测试获取group 2的看板列数量 >> 9
测试获取group 3的看板列数量 >> 9
测试获取group 100001的看板列数量 >> 0

*/

$objectTypeList = array('parent', 'region', '`group`');
$parentIDList   = array('457', '468', '471', '100001');
$regionIDList   = array('1', '2', '3', '100001');
$groupIDList    = array('1', '2', '3', '100001');

$kanban = new kanbanTest();

r($kanban->getColumnsByObjectTest($objectTypeList[0], $parentIDList[0])) && p() && e('2'); // 测试获取parent 457的看板列数量
r($kanban->getColumnsByObjectTest($objectTypeList[0], $parentIDList[1])) && p() && e('2'); // 测试获取parent 468的看板列数量
r($kanban->getColumnsByObjectTest($objectTypeList[0], $parentIDList[2])) && p() && e('2'); // 测试获取parent 471的看板列数量
r($kanban->getColumnsByObjectTest($objectTypeList[0], $parentIDList[3])) && p() && e('0'); // 测试获取parent 100001的看板列数量
r($kanban->getColumnsByObjectTest($objectTypeList[1], $parentIDList[0])) && p() && e('0'); // 测试获取region 1的看板列数量
r($kanban->getColumnsByObjectTest($objectTypeList[1], $parentIDList[1])) && p() && e('0'); // 测试获取region 2的看板列数量
r($kanban->getColumnsByObjectTest($objectTypeList[1], $parentIDList[2])) && p() && e('0'); // 测试获取region 3的看板列数量
r($kanban->getColumnsByObjectTest($objectTypeList[1], $parentIDList[3])) && p() && e('0'); // 测试获取region 100001的看板列数量
r($kanban->getColumnsByObjectTest($objectTypeList[2], $parentIDList[0])) && p() && e('7'); // 测试获取group 1的看板列数量
r($kanban->getColumnsByObjectTest($objectTypeList[2], $parentIDList[1])) && p() && e('9'); // 测试获取group 2的看板列数量
r($kanban->getColumnsByObjectTest($objectTypeList[2], $parentIDList[2])) && p() && e('9'); // 测试获取group 3的看板列数量
r($kanban->getColumnsByObjectTest($objectTypeList[2], $parentIDList[3])) && p() && e('0'); // 测试获取group 100001的看板列数量