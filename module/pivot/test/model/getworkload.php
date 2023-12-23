#!/usr/bin/env php
<?php
/**
title=测试 pivotModel->getWorkload();
cid=1
pid=1

测试部门id为0，执行状态未分配，工时为7的透视表数据是否正常生成,此返回值包含四条数据。                                    >> user1,空,项目集2;user4,空,项目集5
测试部门id为1，执行状态未分配，工时为7的透视表数据是否正常生成, 此返回值只包含一条数据。                                 >> user1,空,项目集2
测试部门id为10000，执行状态未分配，工时为7的透视表数据是否正常生成, 部门id存在但是部门不存在的情况下不会返回数据。       >> 0
测试用户列表为空的情况，如果列表为空，不论传入什么参数，最终返回的都是空数组。                                           >> 0
测试部门id为0，执行状态已分配，工时为7的透视表数据是否正常生成,此返回值包含四条数据。                                    >> 2,0,1,2,28.57;10,1,1,5,71.43
测试部门id为0，执行状态已分配，工时为7.5的透视表数据是否正常生成,此返回值包含四条数据,与上面用例的区别在于负载率的不同。 >> 2,0,1,2,26.67;10,1,1,5,66.67
测试部门id为0，执行状态已分配，工时为8的透视表数据是否正常生成,此返回值包含四条数据,于上边用例的区别在于负载率的不同。   >> 2,0,1,2,25;10,1,1,5,62.5
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';

su('admin');

zdTable('user')->gen(20);
zdTable('dept')->gen(4);
zdTable('task')->config('task')->gen(10);
zdTable('project')->gen(10);
zdTable('project')->config('execution_workload')->gen(10, false, false);
zdTable('team')->config('team')->gen(5);


$pivot = new pivotTest();

global $tester;
$users = $tester->loadModel('user')->getPairs('noletter|noclosed');

$deptList    = array(0, 1, 3);
$assignList  = array('noassign', 'assign');
$allHourList = array(7, 7.5, 8);
$usersList   = array($users, array());

r($pivot->getWorkload($deptList[0], $assignList[0], $usersList[0], $allHourList[0])) && p('0:user,executionName,projectName;3:user,executionName,projectName') && e('user1,空,项目集2;user4,空,项目集5');  //测试部门id为0，执行状态未分配，工时为7的透视表数据是否正常生成,此返回值包含四条数据。
r($pivot->getWorkload($deptList[1], $assignList[0], $usersList[0], $allHourList[0])) && p('0:user,executionName,projectName')                                  && e('user1,空,项目集2');                   //测试部门id为1，执行状态未分配，工时为7的透视表数据是否正常生成, 此返回值只包含一条数据。
r($pivot->getWorkload($deptList[2], $assignList[0], $usersList[0], $allHourList[0])) && p('')                                                                  && e('0');                                  //测试部门id为10000，执行状态未分配，工时为7的透视表数据是否正常生成, 部门id存在但是部门不存在的情况下不会返回数据。
r($pivot->getWorkload($deptList[0], $assignList[1], $usersList[1], $allHourList[1])) && p('')                                                                  && e('0');                                  //测试用户列表为空的情况，如果列表为空，不论传入什么参数，最终返回的都是空数组。

$result = $pivot->getWorkload($deptList[0], $assignList[1], $usersList[0], $allHourList[0]);
foreach($result as $row)
{
    if($row->workload) $row->workload = str_replace('%', '', $row->workload);
    $name = $row->executionName;
    $row->isExecutionNameHtml = 0;
    if(strip_tags($name) != $row->executionName) $row->isExecutionNameHtml = 1; 
}
r($result) && p('0:id,isExecutionNameHtml,totalTasks,totalHours,workload;3:id,isExecutionNameHtml,totalTasks,totalHours,workload') && e("2,0,1,2,28.57;10,1,1,5,71.43");    //测试部门id为0，执行状态已分配，工时为7的透视表数据是否正常生成,此返回值包含四条数据。

$result = $pivot->getWorkload($deptList[0], $assignList[1], $usersList[0], $allHourList[1]);
foreach($result as $row)
{
    if($row->workload) $row->workload = str_replace('%', '', $row->workload);
    $name = $row->executionName;
    $row->isExecutionNameHtml = 0;
    if(strip_tags($name) != $row->executionName) $row->isExecutionNameHtml = 1; 
}
r($result) && p('0:id,isExecutionNameHtml,totalTasks,totalHours,workload;3:id,isExecutionNameHtml,totalTasks,totalHours,workload') && e("2,0,1,2,26.67;10,1,1,5,66.67");    //测试部门id为0，执行状态已分配，工时为7.5的透视表数据是否正常生成,此返回值包含四条数据,与上面用例的区别在于负载率的不同。

$result = $pivot->getWorkload($deptList[0], $assignList[1], $usersList[0], $allHourList[2]);
foreach($result as $row)
{
    if($row->workload) $row->workload = str_replace('%', '', $row->workload);
    $name = $row->executionName;
    $row->isExecutionNameHtml = 0;
    if(strip_tags($name) != $row->executionName) $row->isExecutionNameHtml = 1; 
}
r($result) && p('0:id,isExecutionNameHtml,totalTasks,totalHours,workload;3:id,isExecutionNameHtml,totalTasks,totalHours,workload') && e("2,0,1,2,25;10,1,1,5,62.5");    //测试部门id为0，执行状态已分配，工时为8的透视表数据是否正常生成,此返回值包含四条数据,于上边用例的区别在于负载率的不同。
