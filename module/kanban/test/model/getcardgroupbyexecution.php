#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('project')->loadYaml('kanbanexecution')->gen(5);
zenData('kanbanregion')->loadYaml('rdkanbanregion')->gen(5);
zenData('kanbangroup')->loadYaml('rdkanbangroup')->gen(20);
zenData('kanbancolumn')->gen(20);
zenData('kanbanlane')->loadYaml('rdkanbanlane')->gen(10);
zenData('kanbancell')->loadYaml('rdkanbancell')->gen(20);
zenData('task')->loadYaml('rdkanbantask')->gen(20);

/**

title=测试 kanbanModel->getCardGroupByExecution();
timeout=0
cid=16905

- 测试查询execution1的卡片数量 @2
- 测试查询execution1的task卡片数量 @2
- 测试查询execution1的bug卡片数量 @0
- 测试查询execution1的story卡片数量 @0
- 测试查询execution2的卡片数量 @2
- 测试查询execution2的task卡片数量 @2
- 测试查询execution2的bug卡片数量 @0
- 测试查询execution2的story卡片数量 @0
- 测试查询execution3的卡片数量 @2
- 测试查询execution4的卡片数量 @2
- 测试查询execution5的卡片数量 @2

*/

$executionIDList = array(1, 2, 3, 4, 5);
$browseTypeList  = array('task', 'bug', 'story');

$kanban = new kanbanModelTest();
r($kanban->getCardGroupByExecutionTest($executionIDList[0]))                     && p() && e('2'); // 测试查询execution1的卡片数量
r($kanban->getCardGroupByExecutionTest($executionIDList[0], $browseTypeList[0])) && p() && e('2'); // 测试查询execution1的task卡片数量
r($kanban->getCardGroupByExecutionTest($executionIDList[0], $browseTypeList[1])) && p() && e('0'); // 测试查询execution1的bug卡片数量
r($kanban->getCardGroupByExecutionTest($executionIDList[0], $browseTypeList[2])) && p() && e('0'); // 测试查询execution1的story卡片数量
r($kanban->getCardGroupByExecutionTest($executionIDList[1]))                     && p() && e('2'); // 测试查询execution2的卡片数量
r($kanban->getCardGroupByExecutionTest($executionIDList[1], $browseTypeList[0])) && p() && e('2'); // 测试查询execution2的task卡片数量
r($kanban->getCardGroupByExecutionTest($executionIDList[1], $browseTypeList[1])) && p() && e('0'); // 测试查询execution2的bug卡片数量
r($kanban->getCardGroupByExecutionTest($executionIDList[1], $browseTypeList[2])) && p() && e('0'); // 测试查询execution2的story卡片数量
r($kanban->getCardGroupByExecutionTest($executionIDList[2]))                     && p() && e('2'); // 测试查询execution3的卡片数量
r($kanban->getCardGroupByExecutionTest($executionIDList[3]))                     && p() && e('2'); // 测试查询execution4的卡片数量
r($kanban->getCardGroupByExecutionTest($executionIDList[4]))                     && p() && e('2'); // 测试查询execution5的卡片数量