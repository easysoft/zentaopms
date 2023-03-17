#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getLanes4Group();
cid=1
pid=1

获取执行101 story pri的泳道 >> ,优先级: 无,2,4
获取执行101 story category的泳道 >> ,类型: 无,功能
获取执行101 story module的泳道 >> ,所属模块: 无,产品模块2,产品模块4
获取执行101 story source的泳道 >> ,来源: 无,用户,市场
获取执行102 task pri的泳道 >> ,优先级: 无,1,2,4
获取执行102 task module的泳道 >> ,所属模块: 无,模块4,模块10,模块13,模块16
获取执行102 task assignedTo的泳道 >> ,指派给: 无
获取执行102 task story的泳道 >> ,相关研发需求: 无,用户需求5
获取执行101 bug pri的泳道 >> ,优先级: 无,1,3,4
获取执行101 bug module的泳道 >> ,所属模块: 无,产品模块11,产品模块12,产品模块13
获取执行101 bug assignedTo的泳道 >> ,指派给: 无,admin,测试1
获取执行101 bug severity的泳道 >> ,严重程度: 无,1,3,4
获取执行101 story pri的泳道 >> ,优先级: 无,2,4
获取执行101 story category的泳道 >> ,类型: 无,功能
获取执行101 story module的泳道 >> ,所属模块: 无,产品模块14,产品模块16
获取执行101 story source的泳道 >> ,来源: 无,用户,其他
获取执行102 task pri的泳道 >> ,优先级: 无,1,2,3
获取执行102 task module的泳道 >> ,所属模块: 无,模块13,模块37,模块40,模块43
获取执行102 task assignedTo的泳道 >> ,指派给: 无
获取执行102 task story的泳道 >> ,相关研发需求: 无,用户需求17

*/
$executionIDList = array('101', '102', '103', '104', '105');
$browseTypeList  = array('story', 'task', 'bug');
$groupByList     = array('pri', 'category', 'module', 'source', 'assignedTo', 'story', 'severity');

$kanban = new kanbanTest();

r($kanban->getLanes4GroupTest($executionIDList[0], $browseTypeList[0], $groupByList[0])) && p() && e(',优先级: 无,2,4');                                 // 获取执行101 story pri的泳道
r($kanban->getLanes4GroupTest($executionIDList[0], $browseTypeList[0], $groupByList[1])) && p() && e(',类型: 无,功能');                                  // 获取执行101 story category的泳道
r($kanban->getLanes4GroupTest($executionIDList[0], $browseTypeList[0], $groupByList[2])) && p() && e(',所属模块: 无,产品模块2,产品模块4');               // 获取执行101 story module的泳道
r($kanban->getLanes4GroupTest($executionIDList[0], $browseTypeList[0], $groupByList[3])) && p() && e(',来源: 无,用户,市场');                             // 获取执行101 story source的泳道
r($kanban->getLanes4GroupTest($executionIDList[1], $browseTypeList[1], $groupByList[0])) && p() && e(',优先级: 无,1,2,4');                               // 获取执行102 task pri的泳道
r($kanban->getLanes4GroupTest($executionIDList[1], $browseTypeList[1], $groupByList[2])) && p() && e(',所属模块: 无,模块4,模块10,模块13,模块16');        // 获取执行102 task module的泳道
r($kanban->getLanes4GroupTest($executionIDList[1], $browseTypeList[1], $groupByList[4])) && p() && e(',指派给: 无');                                     // 获取执行102 task assignedTo的泳道
r($kanban->getLanes4GroupTest($executionIDList[1], $browseTypeList[1], $groupByList[5])) && p() && e(',相关研发需求: 无,用户需求5');                     // 获取执行102 task story的泳道
r($kanban->getLanes4GroupTest($executionIDList[2], $browseTypeList[2], $groupByList[0])) && p() && e(',优先级: 无,1,3,4');                               // 获取执行101 bug pri的泳道
r($kanban->getLanes4GroupTest($executionIDList[2], $browseTypeList[2], $groupByList[2])) && p() && e(',所属模块: 无,产品模块11,产品模块12,产品模块13');  // 获取执行101 bug module的泳道
r($kanban->getLanes4GroupTest($executionIDList[2], $browseTypeList[2], $groupByList[4])) && p() && e(',指派给: 无,admin,测试1');                         // 获取执行101 bug assignedTo的泳道
r($kanban->getLanes4GroupTest($executionIDList[2], $browseTypeList[2], $groupByList[6])) && p() && e(',严重程度: 无,1,3,4');                             // 获取执行101 bug severity的泳道
r($kanban->getLanes4GroupTest($executionIDList[3], $browseTypeList[0], $groupByList[0])) && p() && e(',优先级: 无,2,4');                                 // 获取执行101 story pri的泳道
r($kanban->getLanes4GroupTest($executionIDList[3], $browseTypeList[0], $groupByList[1])) && p() && e(',类型: 无,功能');                                  // 获取执行101 story category的泳道
r($kanban->getLanes4GroupTest($executionIDList[3], $browseTypeList[0], $groupByList[2])) && p() && e(',所属模块: 无,产品模块14,产品模块16');             // 获取执行101 story module的泳道
r($kanban->getLanes4GroupTest($executionIDList[3], $browseTypeList[0], $groupByList[3])) && p() && e(',来源: 无,用户,其他');                             // 获取执行101 story source的泳道
r($kanban->getLanes4GroupTest($executionIDList[4], $browseTypeList[1], $groupByList[0])) && p() && e(',优先级: 无,1,2,3');                               // 获取执行102 task pri的泳道
r($kanban->getLanes4GroupTest($executionIDList[4], $browseTypeList[1], $groupByList[2])) && p() && e(',所属模块: 无,模块13,模块37,模块40,模块43');       // 获取执行102 task module的泳道
r($kanban->getLanes4GroupTest($executionIDList[4], $browseTypeList[1], $groupByList[4])) && p() && e(',指派给: 无');                                     // 获取执行102 task assignedTo的泳道
r($kanban->getLanes4GroupTest($executionIDList[4], $browseTypeList[1], $groupByList[5])) && p() && e(',相关研发需求: 无,用户需求17');                    // 获取执行102 task story的泳道
