#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::apiGetIssues();
timeout=0
cid=18376

- 步骤1：正常sonarqubeID和项目key获取问题列表 @1
- 步骤2：验证问题列表第一项的key属性存在 @1
- 步骤3：无效sonarqubeID为0获取问题列表 @return empty
- 步骤4：空项目key参数获取问题列表 @1
- 步骤5：负数sonarqubeID边界值测试 @return empty

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('pipeline')->loadYaml('pipeline')->gen(5);

su('admin');

$sonarqubeTest = new sonarqubeModelTest();

$result1 = $sonarqubeTest->apiGetIssuesTest(2, 'bendi');
$result2 = $sonarqubeTest->apiGetIssuesTest(1, 'testproject');
$result3 = $sonarqubeTest->apiGetIssuesTest(0, 'bendi');
$result4 = $sonarqubeTest->apiGetIssuesTest(2, '');
$result5 = $sonarqubeTest->apiGetIssuesTest(-1, 'test');

r(count($result1) > 0) && p() && e('1');                              // 步骤1：正常sonarqubeID和项目key获取问题列表
r(isset($result1[0]->key) && !empty($result1[0]->key)) && p() && e('1'); // 步骤2：验证问题列表第一项的key属性存在
r($result3) && p() && e('return empty');                               // 步骤3：无效sonarqubeID为0获取问题列表
r(count($result4) > 0) && p() && e('1');                              // 步骤4：空项目key参数获取问题列表
r($result5) && p() && e('return empty');                               // 步骤5：负数sonarqubeID边界值测试