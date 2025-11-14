#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::apiGetQualitygate();
timeout=0
cid=18378

- 执行sonarqubeTest模块的apiGetQualitygateTest方法，参数是0, ''  @return empty
- 执行sonarqubeTest模块的apiGetQualitygateTest方法，参数是21, ''  @return empty
- 执行sonarqubeTest模块的apiGetQualitygateTest方法，参数是22, 'nonexistent_project'  @return empty
- 执行sonarqubeTest模块的apiGetQualitygateTest方法，参数是23, 'test_project'  @return empty
- 执行sonarqubeTest模块的apiGetQualitygateTest方法，参数是-1, 'test_project'  @return empty
- 执行sonarqubeTest模块的apiGetQualitygateTest方法，参数是99999, 'test_project'  @return empty
- 执行sonarqubeTest模块的apiGetQualitygateTest方法，参数是22, 'test_invalid_key'  @return empty

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sonarqube.unittest.class.php';

// 准备测试数据
$table = zenData('pipeline');
$table->id->range('21-25');
$table->type->range('sonarqube{5}');
$table->name->range('Test SonarQube 1, Test SonarQube 2, Test SonarQube 3, Test SonarQube 4, Test SonarQube 5');
$table->url->range('https://sonar1.test.com, https://sonar2.test.com, https://sonar3.test.com, https://sonar4.test.com, https://sonar5.test.com');
$table->account->range('admin{5}');
$table->token->range('test_token_1, test_token_2, test_token_3, test_token_4, test_token_5');
$table->deleted->range('0{5}');
$table->gen(5);

// 用户登录
su('admin');

// 创建测试实例
$sonarqubeTest = new sonarqubeTest();

// 测试步骤1：无效的sonarqubeID和空项目key
r($sonarqubeTest->apiGetQualitygateTest(0, '')) && p() && e('return empty');

// 测试步骤2：有效的sonarqubeID但项目key为空
r($sonarqubeTest->apiGetQualitygateTest(21, '')) && p() && e('return empty');

// 测试步骤3：有效的sonarqubeID但不存在的项目key
r($sonarqubeTest->apiGetQualitygateTest(22, 'nonexistent_project')) && p() && e('return empty');

// 测试步骤4：有效的sonarqubeID和有效的项目key
r($sonarqubeTest->apiGetQualitygateTest(23, 'test_project')) && p() && e('return empty');

// 测试步骤5：负数的sonarqubeID
r($sonarqubeTest->apiGetQualitygateTest(-1, 'test_project')) && p() && e('return empty');

// 测试步骤6：超大的sonarqubeID
r($sonarqubeTest->apiGetQualitygateTest(99999, 'test_project')) && p() && e('return empty');

// 测试步骤7：特殊字符项目key测试
r($sonarqubeTest->apiGetQualitygateTest(22, 'test_invalid_key')) && p() && e('return empty');