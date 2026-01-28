#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::apiGetProjects();
timeout=0
cid=18377

- 步骤1：正常情况第0条的name属性 @Special Key Test
- 步骤2：关键字搜索第0条的key属性 @special.key:test-123
- 步骤3：projectKey查询 @return empty
- 步骤4：无效ID(0) @return empty
- 步骤5：无效ID(999) @return empty
- 步骤6：组合参数 @return empty
- 步骤7：空关键字第0条的name属性 @Special Key Test

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
zenData('pipeline')->loadYaml('pipeline')->gen(5);

// 设置用户登录
su('admin');

// 创建测试实例
$sonarqube = new sonarqubeModelTest();

// 测试参数定义
$validSonarqubeID = 2;
$invalidSonarqubeID1 = 0;
$invalidSonarqubeID2 = 999;
$keyword = 'test';
$projectKey = 'unittest-project';
$emptyKeyword = '';

// 测试步骤1：正常sonarqubeID获取项目列表
r($sonarqube->apiGetProjectsTest($validSonarqubeID)) && p('0:name') && e('Special Key Test'); // 步骤1：正常情况

// 测试步骤2：使用关键字搜索项目
r($sonarqube->apiGetProjectsTest($validSonarqubeID, $keyword)) && p('0:key') && e('special.key:test-123'); // 步骤2：关键字搜索

// 测试步骤3：使用projectKey参数获取特定项目
r($sonarqube->apiGetProjectsTest($validSonarqubeID, '', $projectKey)) && p() && e('return empty'); // 步骤3：projectKey查询

// 测试步骤4：无效sonarqubeID（0）测试
r($sonarqube->apiGetProjectsTest($invalidSonarqubeID1)) && p() && e('return empty'); // 步骤4：无效ID(0)

// 测试步骤5：无效sonarqubeID（999）测试
r($sonarqube->apiGetProjectsTest($invalidSonarqubeID2)) && p() && e('return empty'); // 步骤5：无效ID(999)

// 测试步骤6：同时使用keyword和projectKey参数
r($sonarqube->apiGetProjectsTest($validSonarqubeID, $keyword, $projectKey)) && p() && e('return empty'); // 步骤6：组合参数

// 测试步骤7：使用空关键字搜索（相当于获取所有项目）
r($sonarqube->apiGetProjectsTest($validSonarqubeID, $emptyKeyword)) && p('0:name') && e('Special Key Test'); // 步骤7：空关键字