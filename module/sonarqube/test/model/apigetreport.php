#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::apiGetReport();
cid=0

- 通过错误的sonarqubeID、projectKey，查询sonarqube报告 @return empty
- 通过正确的sonarqubeID,不存在的projectKey查询sonarqube报告第0条的msg属性 @Component key 'wrong_project' not found
- 通过正确的sonarqubeID、projectKey，不正确的metricKeys查询sonarqube报告第0条的msg属性 @The following metric keys are not found: wrong_params
- 通过正确的sonarqubeID、projectKey、metricKeys，查询sonarqube报告 @success

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('pipeline')->config('pipeline')->gen(5);

$sonarqube = $tester->loadModel('sonarqube');

$sonarqubeID = 0;
$projectKey  = '';
$result      = $sonarqube->apiGetReport($sonarqubeID, $projectKey);
if(empty($result)) $result = 'return empty';
r($result) && p() && e('return empty'); //通过错误的sonarqubeID、projectKey，查询sonarqube报告

$sonarqubeID = 2;
$projectKey  = 'wrong_project';
$result      = $sonarqube->apiGetReport($sonarqubeID, $projectKey);
r($result->errors) && p('0:msg') && e("Component key 'wrong_project' not found"); //通过正确的sonarqubeID,不存在的projectKey查询sonarqube报告

$projectKey = 'unittest';
$metricKeys = 'wrong_params';
$result     = $sonarqube->apiGetReport($sonarqubeID, $projectKey, $metricKeys);
r($result->errors) && p('0:msg') && e("The following metric keys are not found: wrong_params"); //通过正确的sonarqubeID、projectKey，不正确的metricKeys查询sonarqube报告

$metricKeys = 'bugs';
$result     = $sonarqube->apiGetReport($sonarqubeID, $projectKey, $metricKeys);
if(!empty($result->component)) $result = 'success';
r($result) && p() && e('success'); //通过正确的sonarqubeID、projectKey、metricKeys，查询sonarqube报告
