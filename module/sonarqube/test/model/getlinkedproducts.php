#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::getLinkedProducts();
timeout=0
cid=18385

- 步骤1：无效sonarqubeID和空projectKey @0
- 步骤2：有效sonarqubeID但无效projectKey @0
- 步骤3：无效sonarqubeID但有效projectKey @0
- 步骤4：有效参数但无匹配数据 @0
- 步骤5：不同sonarqubeServer的情况 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sonarqube.unittest.class.php';

zenData('pipeline')->loadYaml('pipeline')->gen(5);

$job = zenData('job');
$job->id->range('1-10');
$job->name->range('sonarqube任务1,sonarqube任务2,jenkins任务1,其他任务1,sonarqube任务3');
$job->repo->range('1,2,0,0,3');
$job->frame->range('sonarqube,sonarqube,jenkins,custom,sonarqube');
$job->sonarqubeServer->range('1,2,0,0,2');
$job->projectKey->range(',zentaopms,,testproject,zentaopms');
$job->gen(5);

$repo = zenData('repo');
$repo->id->range('1-3');
$repo->product->range('1,2,3');
$repo->gen(3);

su('admin');

$sonarqubeTest = new sonarqubeTest();

r($sonarqubeTest->getLinkedProductsTest(0, '')) && p() && e('0'); // 步骤1：无效sonarqubeID和空projectKey
r($sonarqubeTest->getLinkedProductsTest(2, 'nonexistent')) && p() && e('0'); // 步骤2：有效sonarqubeID但无效projectKey
r($sonarqubeTest->getLinkedProductsTest(99, 'zentaopms')) && p() && e('0'); // 步骤3：无效sonarqubeID但有效projectKey
r($sonarqubeTest->getLinkedProductsTest(2, 'zentaopms')) && p() && e('0'); // 步骤4：有效参数但无匹配数据
r($sonarqubeTest->getLinkedProductsTest(1, 'zentaopms')) && p() && e('1'); // 步骤5：不同sonarqubeServer的情况