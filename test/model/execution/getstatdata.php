#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试 executionModel->getStatData();
cid=1
pid=1

测试默认值 >> 0
测试传入空值 >> 0
测试projectID为1的所有未关闭执行 >> 0
测试projectID为1的所有执行 >> 0
测试projectID为1的未开始执行 >> 0
测试projectID为1的进行中执行 >> 0
测试projectID为1的已挂起执行 >> 0
测试projectID为1的已关闭执行 >> 0
测试projectID为1的我参与执行 >> 0
测试projectID为1的搜索出来的执行 >> 0
测试projectID为1的评审执行 >> 0
测试projectID为1, productID为1, branchID为0的所有执行 >> 0
测试projectID为1, productID为1, branchID为0的所有执行和任务 >> 0
测试projectID为1, productID为1, branchID为0的非父阶段 >> 0
测试projectID为11的所有未关闭执行 >> 7
测试projectID为11的所有执行 >> 7
测试projectID为11的未开始执行 >> 7
测试projectID为11的进行中执行 >> 7
测试projectID为11的已挂起执行 >> 7
测试projectID为11的已关闭执行 >> 7
测试projectID为11的我参与执行 >> 7
测试projectID为11的搜索出来的执行 >> 7
测试projectID为11的评审执行 >> 7
测试projectID为11, productID为1, branchID为0的所有执行 >> 7
测试projectID为11, productID为1, branchID为0的所有执行和任务 >> 7
测试projectID为11, productID为1, branchID为0的非父阶段 >> 7

*/

$projectIdList  = array(0, 1, 11);
$browseTypeList = array('all', 'wait', 'doing', 'suspended', 'closed', 'involved', 'bySearch', 'review');
$productID      = 1;
$branchID       = 0;
$withTasksList  = array(false, true);
$param          = 'skipParent';

$execution = new executionTest();

r($execution->getStatDataTest())                                                                                        && p() && e('0'); // 测试默认值
r($execution->getStatDataTest($projectIdList[0]))                                                                       && p() && e('0'); // 测试传入空值
r($execution->getStatDataTest($projectIdList[1]))                                                                       && p() && e('0'); // 测试projectID为1的所有未关闭执行
r($execution->getStatDataTest($projectIdList[1], $browseTypeList[0]))                                                   && p() && e('0'); // 测试projectID为1的所有执行
r($execution->getStatDataTest($projectIdList[1], $browseTypeList[1]))                                                   && p() && e('0'); // 测试projectID为1的未开始执行
r($execution->getStatDataTest($projectIdList[1], $browseTypeList[2]))                                                   && p() && e('0'); // 测试projectID为1的进行中执行
r($execution->getStatDataTest($projectIdList[1], $browseTypeList[3]))                                                   && p() && e('0'); // 测试projectID为1的已挂起执行
r($execution->getStatDataTest($projectIdList[1], $browseTypeList[4]))                                                   && p() && e('0'); // 测试projectID为1的已关闭执行
r($execution->getStatDataTest($projectIdList[1], $browseTypeList[5]))                                                   && p() && e('0'); // 测试projectID为1的我参与执行
r($execution->getStatDataTest($projectIdList[1], $browseTypeList[6]))                                                   && p() && e('0'); // 测试projectID为1的搜索出来的执行
r($execution->getStatDataTest($projectIdList[1], $browseTypeList[7]))                                                   && p() && e('0'); // 测试projectID为1的评审执行
r($execution->getStatDataTest($projectIdList[1], $browseTypeList[0], $productID, $branchID))                            && p() && e('0'); // 测试projectID为1, productID为1, branchID为0的所有执行
r($execution->getStatDataTest($projectIdList[1], $browseTypeList[0], $productID, $branchID), $withTasksList[1])         && p() && e('0'); // 测试projectID为1, productID为1, branchID为0的所有执行和任务
r($execution->getStatDataTest($projectIdList[1], $browseTypeList[0], $productID, $branchID), $withTasksList[0], $param) && p() && e('0'); // 测试projectID为1, productID为1, branchID为0的非父阶段
r($execution->getStatDataTest($projectIdList[2]))                                                                       && p() && e('7'); // 测试projectID为11的所有未关闭执行
r($execution->getStatDataTest($projectIdList[2], $browseTypeList[0]))                                                   && p() && e('7'); // 测试projectID为11的所有执行
r($execution->getStatDataTest($projectIdList[2], $browseTypeList[1]))                                                   && p() && e('7'); // 测试projectID为11的未开始执行
r($execution->getStatDataTest($projectIdList[2], $browseTypeList[2]))                                                   && p() && e('7'); // 测试projectID为11的进行中执行
r($execution->getStatDataTest($projectIdList[2], $browseTypeList[3]))                                                   && p() && e('7'); // 测试projectID为11的已挂起执行
r($execution->getStatDataTest($projectIdList[2], $browseTypeList[4]))                                                   && p() && e('7'); // 测试projectID为11的已关闭执行
r($execution->getStatDataTest($projectIdList[2], $browseTypeList[5]))                                                   && p() && e('7'); // 测试projectID为11的我参与执行
r($execution->getStatDataTest($projectIdList[2], $browseTypeList[6]))                                                   && p() && e('7'); // 测试projectID为11的搜索出来的执行
r($execution->getStatDataTest($projectIdList[2], $browseTypeList[7]))                                                   && p() && e('7'); // 测试projectID为11的评审执行
r($execution->getStatDataTest($projectIdList[2], $browseTypeList[0], $productID, $branchID))                            && p() && e('7'); // 测试projectID为11, productID为1, branchID为0的所有执行
r($execution->getStatDataTest($projectIdList[2], $browseTypeList[0], $productID, $branchID), $withTasksList[1])         && p() && e('7'); // 测试projectID为11, productID为1, branchID为0的所有执行和任务
r($execution->getStatDataTest($projectIdList[2], $browseTypeList[0], $productID, $branchID), $withTasksList[0], $param) && p() && e('7'); // 测试projectID为11, productID为1, branchID为0的非父阶段
