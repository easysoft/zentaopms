#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 projectModel->hasStageData();
timeout=0
cid=17860

- 测试传入执行ID为0的情况 @0
- 测试有子阶段的情况 @0
- 测试子阶段没有数据的情况 @0
- 测试有任务数据的情况 @1
- 测试有日志数据的情况 @1
- 测试有Bug数据的情况 @1
- 测试有需求数据的情况 @1
- 测试有用例数据的情况 @1
- 测试有版本数据的情况 @1
- 测试有测试单数据的情况 @1
- 测试有测试报告数据的情况 @1
- 测试有文档数据的情况 @1
- 测试有非文档主库数据的情况 @1
- 测试有模块数据的情况 @1

*/

zenData('project')->loadYaml('project')->gen(14)->fixPath();
zenData('task')->loadYaml('task')->gen(2);
zenData('effort')->loadYaml('effort')->gen(2);
zenData('bug')->loadYaml('bug')->gen(2);
zenData('story')->loadYaml('story')->gen(2);
zenData('projectstory')->loadYaml('projectstory')->gen(2);
zenData('case')->loadYaml('case')->gen(2);
zenData('projectcase')->loadYaml('projectcase')->gen(2);
zenData('build')->loadYaml('build')->gen(2);
zenData('testtask')->loadYaml('testtask')->gen(2);
zenData('testreport')->loadYaml('testreport')->gen(2);
zenData('doclib')->loadYaml('doclib')->gen(5);
zenData('doc')->loadYaml('doc')->gen(2);
zenData('module')->loadYaml('module')->gen(6);

$executionIdList = array(0) + range(1, 14);

global $tester;
$projectModel = $tester->loadModel('project');
r($projectModel->hasStageData($executionIdList[0]))  && p() && e('0'); // 测试传入执行ID为0的情况
r($projectModel->hasStageData($executionIdList[1]))  && p() && e('0'); // 测试有子阶段的情况
r($projectModel->hasStageData($executionIdList[2]))  && p() && e('0'); // 测试子阶段没有数据的情况
r($projectModel->hasStageData($executionIdList[3]))  && p() && e('1'); // 测试有任务数据的情况
r($projectModel->hasStageData($executionIdList[4]))  && p() && e('1'); // 测试有日志数据的情况
r($projectModel->hasStageData($executionIdList[5]))  && p() && e('1'); // 测试有Bug数据的情况
r($projectModel->hasStageData($executionIdList[6]))  && p() && e('1'); // 测试有需求数据的情况
r($projectModel->hasStageData($executionIdList[7]))  && p() && e('1'); // 测试有用例数据的情况
r($projectModel->hasStageData($executionIdList[8]))  && p() && e('1'); // 测试有版本数据的情况
r($projectModel->hasStageData($executionIdList[9]))  && p() && e('1'); // 测试有测试单数据的情况
r($projectModel->hasStageData($executionIdList[10])) && p() && e('1'); // 测试有测试报告数据的情况
r($projectModel->hasStageData($executionIdList[11])) && p() && e('1'); // 测试有文档数据的情况
r($projectModel->hasStageData($executionIdList[12])) && p() && e('1'); // 测试有非文档主库数据的情况
r($projectModel->hasStageData($executionIdList[13])) && p() && e('1'); // 测试有模块数据的情况