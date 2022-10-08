#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getCardGroupByExecution();
cid=1
pid=1

测试查询execution161的卡片 >> 5
测试查询execution161的task卡片 >> 1
测试查询execution161的bug卡片 >> 3
测试查询execution161的story卡片 >> 1
测试查询execution162的卡片 >> 5
测试查询execution162的task卡片 >> 1
测试查询execution162的bug卡片 >> 3
测试查询execution162的story卡片 >> 1
测试查询execution163的卡片 >> 4
测试查询execution164的卡片 >> 4
测试查询execution165的卡片 >> 0

*/

$executionIDList = array('161', '162', '163', '164', '165');
$browseTypeList  = array('task', 'bug', 'story');

$kanban = new kanbanTest();
r($kanban->getCardGroupByExecutionTest($executionIDList[0]))                     && p() && e('5'); // 测试查询execution161的卡片
r($kanban->getCardGroupByExecutionTest($executionIDList[0], $browseTypeList[0])) && p() && e('1'); // 测试查询execution161的task卡片
r($kanban->getCardGroupByExecutionTest($executionIDList[0], $browseTypeList[1])) && p() && e('3'); // 测试查询execution161的bug卡片
r($kanban->getCardGroupByExecutionTest($executionIDList[0], $browseTypeList[2])) && p() && e('1'); // 测试查询execution161的story卡片
r($kanban->getCardGroupByExecutionTest($executionIDList[1]))                     && p() && e('5'); // 测试查询execution162的卡片
r($kanban->getCardGroupByExecutionTest($executionIDList[1], $browseTypeList[0])) && p() && e('1'); // 测试查询execution162的task卡片
r($kanban->getCardGroupByExecutionTest($executionIDList[1], $browseTypeList[1])) && p() && e('3'); // 测试查询execution162的bug卡片
r($kanban->getCardGroupByExecutionTest($executionIDList[1], $browseTypeList[2])) && p() && e('1'); // 测试查询execution162的story卡片
r($kanban->getCardGroupByExecutionTest($executionIDList[2]))                     && p() && e('4'); // 测试查询execution163的卡片
r($kanban->getCardGroupByExecutionTest($executionIDList[3]))                     && p() && e('4'); // 测试查询execution164的卡片
r($kanban->getCardGroupByExecutionTest($executionIDList[4]))                     && p() && e('0'); // 测试查询execution165的卡片