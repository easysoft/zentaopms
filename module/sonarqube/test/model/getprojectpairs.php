#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::getProjectPairs();
timeout=0
cid=0

- 步骤1：无效sonarqubeID @0
- 步骤2：有效sonarqubeID，检查项目数 @8
- 步骤3：验证特定项目属性bendi @本地项目
- 步骤4：指定projectKey参数属性bendi @本地项目
- 步骤5：空projectKey参数 @8

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sonarqube.unittest.class.php';

zenData('pipeline')->loadYaml('pipeline')->gen(5);
zenData('job')->gen(0);

su('admin');

$sonarqubeTest = new sonarqubeTest();

r($sonarqubeTest->getProjectPairsTest(0)) && p() && e('0'); // 步骤1：无效sonarqubeID
r(count($sonarqubeTest->getProjectPairsTest(2))) && p() && e('8'); // 步骤2：有效sonarqubeID，检查项目数
r($sonarqubeTest->getProjectPairsTest(2)) && p('bendi') && e('本地项目'); // 步骤3：验证特定项目
r($sonarqubeTest->getProjectPairsTest(2, 'bendi')) && p('bendi') && e('本地项目'); // 步骤4：指定projectKey参数
r(count($sonarqubeTest->getProjectPairsTest(2, ''))) && p() && e('8'); // 步骤5：空projectKey参数