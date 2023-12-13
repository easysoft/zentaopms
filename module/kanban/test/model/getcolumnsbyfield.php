#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancolumn')->gen(100);

/**

title=测试 kanbanModel->getColumnsByField();
timeout=0
cid=1

- 测试获取parent 457的看板列数量 @0
- 测试获取parent 468的看板列数量 @0
- 测试获取parent 471的看板列数量 @0
- 测试获取parent 100001的看板列数量 @0
- 测试获取region 1的看板列数量 @4
- 测试获取region 2的看板列数量 @4
- 测试获取region 3的看板列数量 @4
- 测试获取region 100001的看板列数量 @0
- 测试获取group 1的看板列数量 @4
- 测试获取group 2的看板列数量 @4
- 测试获取group 3的看板列数量 @4
- 测试获取group 100001的看板列数量 @0

*/

$fieldList    = array('parent', 'region', '`group`');
$parentIDList = array('457', '468', '471', '100001');
$regionIDList = array('1', '2', '3', '100001');
$groupIDList  = array('1', '2', '3', '100001');

$kanban = new kanbanTest();

r($kanban->getColumnsByFieldTest($fieldList[0], $parentIDList[0])) && p() && e('0'); // 测试获取parent 457的看板列数量
r($kanban->getColumnsByFieldTest($fieldList[0], $parentIDList[1])) && p() && e('0'); // 测试获取parent 468的看板列数量
r($kanban->getColumnsByFieldTest($fieldList[0], $parentIDList[2])) && p() && e('0'); // 测试获取parent 471的看板列数量
r($kanban->getColumnsByFieldTest($fieldList[0], $parentIDList[3])) && p() && e('0'); // 测试获取parent 100001的看板列数量
r($kanban->getColumnsByFieldTest($fieldList[1], $regionIDList[0])) && p() && e('4'); // 测试获取region 1的看板列数量
r($kanban->getColumnsByFieldTest($fieldList[1], $regionIDList[1])) && p() && e('4'); // 测试获取region 2的看板列数量
r($kanban->getColumnsByFieldTest($fieldList[1], $regionIDList[2])) && p() && e('4'); // 测试获取region 3的看板列数量
r($kanban->getColumnsByFieldTest($fieldList[1], $regionIDList[3])) && p() && e('0'); // 测试获取region 100001的看板列数量
r($kanban->getColumnsByFieldTest($fieldList[2], $groupIDList[0]))  && p() && e('4'); // 测试获取group 1的看板列数量
r($kanban->getColumnsByFieldTest($fieldList[2], $groupIDList[1]))  && p() && e('4'); // 测试获取group 2的看板列数量
r($kanban->getColumnsByFieldTest($fieldList[2], $groupIDList[2]))  && p() && e('4'); // 测试获取group 3的看板列数量
r($kanban->getColumnsByFieldTest($fieldList[2], $groupIDList[3]))  && p() && e('0'); // 测试获取group 100001的看板列数量