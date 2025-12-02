#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::apiGetReport();
timeout=0
cid=18379

- 步骤1：无效sonarqubeID和空projectKey @return empty
- 步骤2：不存在的sonarqubeID @return empty
- 步骤3：不存在的projectKey第0条的msg属性 @Component key 'wrong_project' not found
- 步骤4：无效的metricKeys第0条的msg属性 @The following metric keys are not found: invalid_metric
- 步骤5：使用默认metricKeys第component条的key属性 @unittest
- 步骤6：使用单个有效metricKeys第component条的key属性 @unittest
- 步骤7：使用多个有效metricKeys第component条的key属性 @unittest

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sonarqube.unittest.class.php';

// 2. zendata数据准备
zenData('pipeline')->loadYaml('pipeline')->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$sonarqubeTest = new sonarqubeTest();

// 5. 测试步骤：必须包含至少5个测试步骤
r($sonarqubeTest->apiGetReportTest(0, '')) && p() && e('return empty'); // 步骤1：无效sonarqubeID和空projectKey
r($sonarqubeTest->apiGetReportTest(999, 'nonexistent')) && p() && e('return empty'); // 步骤2：不存在的sonarqubeID
r($sonarqubeTest->apiGetReportTest(2, 'wrong_project')) && p('0:msg') && e("Component key 'wrong_project' not found"); // 步骤3：不存在的projectKey
r($sonarqubeTest->apiGetReportTest(2, 'unittest', 'invalid_metric')) && p('0:msg') && e("The following metric keys are not found: invalid_metric"); // 步骤4：无效的metricKeys
r($sonarqubeTest->apiGetReportTest(2, 'unittest', '')) && p('component:key') && e('unittest'); // 步骤5：使用默认metricKeys
r($sonarqubeTest->apiGetReportTest(2, 'unittest', 'bugs')) && p('component:key') && e('unittest'); // 步骤6：使用单个有效metricKeys
r($sonarqubeTest->apiGetReportTest(2, 'unittest', 'bugs,coverage,vulnerabilities')) && p('component:key') && e('unittest'); // 步骤7：使用多个有效metricKeys