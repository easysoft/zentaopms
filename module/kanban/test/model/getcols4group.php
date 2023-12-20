#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('project')->config('kanbanexecution')->gen(5);
zdTable('kanbanregion')->config('rdkanbanregion')->gen(5);
zdTable('kanbangroup')->config('rdkanbangroup')->gen(20);
zdTable('kanbancolumn')->gen(20);
zdTable('kanbanlane')->config('rdkanbanlane')->gen(10);
zdTable('kanbancell')->config('rdkanbancell')->gen(20);

/**

title=测试 kanbanModel->getCols4Group();
timeout=0
cid=1

- 获取执行1 story pri的泳道 @0
- 获取执行1 story category的泳道
 - 第column1条的id属性 @1
 - 第column1条的columnName属性 @未开始
- 获取执行1 story module的泳道 @0
- 获取执行2 task pri的泳道 @0
- 获取执行2 task module的泳道
 - 第column2条的id属性 @2
 - 第column2条的columnName属性 @进行中
- 获取执行2 task assignedTo的泳道 @0
- 获取执行1 bug pri的泳道 @0
- 获取执行1 bug module的泳道
 - 第column3条的id属性 @3
 - 第column3条的columnName属性 @已完成
- 获取执行1 bug assignedTo的泳道 @0

*/
$executionIDList = array('1', '2', '3');
$browseTypeList  = array('story', 'task', 'bug');

global $tester;
$tester->loadModel('kanban');

r($tester->kanban->getCols4Group($executionIDList[0], $browseTypeList[0])) && p('')                      && e('0');        // 获取执行1 story pri的泳道
r($tester->kanban->getCols4Group($executionIDList[0], $browseTypeList[1])) && p('column1:id,columnName') && e('1,未开始'); // 获取执行1 story category的泳道
r($tester->kanban->getCols4Group($executionIDList[0], $browseTypeList[2])) && p('')                      && e('0');        // 获取执行1 story module的泳道
r($tester->kanban->getCols4Group($executionIDList[1], $browseTypeList[0])) && p('')                      && e('0');        // 获取执行2 task pri的泳道
r($tester->kanban->getCols4Group($executionIDList[1], $browseTypeList[1])) && p('column2:id,columnName') && e('2,进行中'); // 获取执行2 task module的泳道
r($tester->kanban->getCols4Group($executionIDList[1], $browseTypeList[2])) && p('')                      && e('0');        // 获取执行2 task assignedTo的泳道
r($tester->kanban->getCols4Group($executionIDList[2], $browseTypeList[0])) && p('')                      && e('0');        // 获取执行1 bug pri的泳道
r($tester->kanban->getCols4Group($executionIDList[2], $browseTypeList[1])) && p('column3:id,columnName') && e('3,已完成'); // 获取执行1 bug module的泳道
r($tester->kanban->getCols4Group($executionIDList[2], $browseTypeList[2])) && p('')                      && e('0');        // 获取执行1 bug assignedTo的泳道