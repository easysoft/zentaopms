#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->isClickable();
cid=1
pid=1

测试lane1 是否可以排序 >> 2
测试lane1 是否可以删除 >> 2
测试lane276 是否可以排序 >> 2
测试lane276 是否可以删除 >> 1
测试column1 是否可以拆分子列 >> 1
测试column1 是否可以还原 >> 2
测试column1 是否可以归档 >> 1
测试column1 是否可以删除 >> 1
测试column1 是否可以排序 >> 1
测试column403 是否可以拆分子列 >> 2
测试column403 是否可以还原 >> 2
测试column403 是否可以归档 >> 1
测试column403 是否可以删除 >> 1
测试column403 是否可以排序 >> 1
测试column404 是否可以拆分子列 >> 2
测试column404 是否可以还原 >> 2
测试column404 是否可以归档 >> 1
测试column404 是否可以删除 >> 1
测试column404 是否可以排序 >> 1
测试column405 是否可以拆分子列 >> 2
测试column405 是否可以还原 >> 2
测试column405 是否可以归档 >> 1
测试column405 是否可以删除 >> 1
测试column405 是否可以排序 >> 1

*/

$objectType = array('Lane', 'Column');
$objectID   = array('1', '276', '403', '404', '405');

$actionList = array('sortlane', 'deletelane', 'splitcolumn', 'restorecolumn', 'archivecolumn', 'deletecolumn', 'sortColumn');

$kanban = new kanbanTest();

r($kanban->isClickableTest($objectType[0], $objectID[0], $actionList[0])) && p() && e('2'); // 测试lane1 是否可以排序
r($kanban->isClickableTest($objectType[0], $objectID[0], $actionList[1])) && p() && e('2'); // 测试lane1 是否可以删除
r($kanban->isClickableTest($objectType[0], $objectID[1], $actionList[0])) && p() && e('2'); // 测试lane276 是否可以排序
r($kanban->isClickableTest($objectType[0], $objectID[1], $actionList[1])) && p() && e('1'); // 测试lane276 是否可以删除
r($kanban->isClickableTest($objectType[1], $objectID[0], $actionList[2])) && p() && e('1'); // 测试column1 是否可以拆分子列
r($kanban->isClickableTest($objectType[1], $objectID[0], $actionList[3])) && p() && e('2'); // 测试column1 是否可以还原
r($kanban->isClickableTest($objectType[1], $objectID[0], $actionList[4])) && p() && e('1'); // 测试column1 是否可以归档
r($kanban->isClickableTest($objectType[1], $objectID[0], $actionList[5])) && p() && e('1'); // 测试column1 是否可以删除
r($kanban->isClickableTest($objectType[1], $objectID[0], $actionList[6])) && p() && e('1'); // 测试column1 是否可以排序
r($kanban->isClickableTest($objectType[1], $objectID[2], $actionList[2])) && p() && e('2'); // 测试column403 是否可以拆分子列
r($kanban->isClickableTest($objectType[1], $objectID[2], $actionList[3])) && p() && e('2'); // 测试column403 是否可以还原
r($kanban->isClickableTest($objectType[1], $objectID[2], $actionList[4])) && p() && e('1'); // 测试column403 是否可以归档
r($kanban->isClickableTest($objectType[1], $objectID[2], $actionList[5])) && p() && e('1'); // 测试column403 是否可以删除
r($kanban->isClickableTest($objectType[1], $objectID[2], $actionList[6])) && p() && e('1'); // 测试column403 是否可以排序
r($kanban->isClickableTest($objectType[1], $objectID[3], $actionList[2])) && p() && e('2'); // 测试column404 是否可以拆分子列
r($kanban->isClickableTest($objectType[1], $objectID[3], $actionList[3])) && p() && e('2'); // 测试column404 是否可以还原
r($kanban->isClickableTest($objectType[1], $objectID[3], $actionList[4])) && p() && e('1'); // 测试column404 是否可以归档
r($kanban->isClickableTest($objectType[1], $objectID[3], $actionList[5])) && p() && e('1'); // 测试column404 是否可以删除
r($kanban->isClickableTest($objectType[1], $objectID[3], $actionList[6])) && p() && e('1'); // 测试column404 是否可以排序
r($kanban->isClickableTest($objectType[1], $objectID[4], $actionList[2])) && p() && e('2'); // 测试column405 是否可以拆分子列
r($kanban->isClickableTest($objectType[1], $objectID[4], $actionList[3])) && p() && e('2'); // 测试column405 是否可以还原
r($kanban->isClickableTest($objectType[1], $objectID[4], $actionList[4])) && p() && e('1'); // 测试column405 是否可以归档
r($kanban->isClickableTest($objectType[1], $objectID[4], $actionList[5])) && p() && e('1'); // 测试column405 是否可以删除
r($kanban->isClickableTest($objectType[1], $objectID[4], $actionList[6])) && p() && e('1'); // 测试column405 是否可以排序