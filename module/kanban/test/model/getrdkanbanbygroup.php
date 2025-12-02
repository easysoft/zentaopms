#!/usr/bin/env php
<?php

/**

title=测试 kanbanModel::getRDKanbanByGroup();
timeout=0
cid=16939

- 测试步骤1：正常execution对象分组看板查询第0条的key属性 @region1
- 测试步骤2：不同browseType参数测试第0条的id属性 @1
- 测试步骤3：不同groupBy参数测试第0条的key属性 @region1
- 测试步骤4：包含searchValue参数测试第0条的toggleFromHeading属性 @1
- 测试步骤5：边界值regionID测试第0条的key属性 @region1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 2. zendata数据准备
zenData('user')->gen(10);
zenData('module')->gen(10);
zenData('story')->gen(20);
zenData('bug')->gen(20);
zenData('task')->gen(20);
zenData('project')->loadYaml('kanbanexecution')->gen(5);
zenData('kanbanregion')->loadYaml('rdkanbanregion')->gen(5);
zenData('kanbangroup')->loadYaml('rdkanbangroup')->gen(20);
zenData('kanbancolumn')->gen(20);
zenData('kanbanlane')->loadYaml('rdkanbanlane')->gen(10);
zenData('kanbancell')->loadYaml('rdkanbancell')->gen(20);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$kanbanTest = new kanbanTest();

// 5. 测试步骤（必须包含至少5个）
// 准备execution对象
$testExecution = new stdClass();
$testExecution->id = 1;
$testExecution->name = '看板1';
$testExecution->attribute = 'mix';

r($kanbanTest->getRDKanbanByGroupTest($testExecution, 'story', 'id_asc', 1, 'pri', '')) && p('0:key') && e('region1'); // 测试步骤1：正常execution对象分组看板查询
r($kanbanTest->getRDKanbanByGroupTest($testExecution, 'task', 'id_desc', 1, 'module', '')) && p('0:id') && e('1'); // 测试步骤2：不同browseType参数测试
r($kanbanTest->getRDKanbanByGroupTest($testExecution, 'story', 'pri_asc', 1, 'category', '')) && p('0:key') && e('region1'); // 测试步骤3：不同groupBy参数测试
r($kanbanTest->getRDKanbanByGroupTest($testExecution, 'story', 'id_asc', 1, 'pri', 'test')) && p('0:toggleFromHeading') && e('1'); // 测试步骤4：包含searchValue参数测试
r($kanbanTest->getRDKanbanByGroupTest($testExecution, 'story', 'id_asc', 0, 'pri', '')) && p('0:key') && e('region1'); // 测试步骤5：边界值regionID测试