#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::upgrade();
timeout=0
cid=16826

- 步骤1：正常升级情况（CNE API不可用时返回0） @0
- 步骤2：升级版本为空字符串 @0
- 步骤3：应用版本为空字符串 @0
- 步骤4：实例对象缺少必要字段 @0
- 步骤5：升级到相同版本 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$instanceTable = zenData('instance');
$instanceTable->id->range('1-5');
$instanceTable->space->range('1{5}');
$instanceTable->name->range('GitLab,Subversion,Jenkins,Nexus,SonarQube');
$instanceTable->appID->range('42,156,89,123,67');
$instanceTable->appName->range('GitLab,Subversion,Jenkins,Nexus,SonarQube');
$instanceTable->appVersion->range('15.3.4,1.14.2,2.401.3,3.43.0,9.9');
$instanceTable->chart->range('gitlab,subversion,jenkins,nexus3,sonarqube');
$instanceTable->version->range('2023.10.901,2023.12.1201,2024.01.501,2024.02.801,2024.03.901');
$instanceTable->status->range('running{5}');
$instanceTable->k8name->range('gitlab-test,subversion-test,jenkins-test,nexus-test,sonar-test');
$instanceTable->domain->range('gitlab.test.com,svn.test.com,jenkins.test.com,nexus.test.com,sonar.test.com');
$instanceTable->source->range('cloud{5}');
$instanceTable->channel->range('stable{5}');
$instanceTable->deleted->range('0{5}');
$instanceTable->gen(5);

$spaceTable = zenData('space');
$spaceTable->id->range('1');
$spaceTable->name->range('default');
$spaceTable->k8space->range('quickon-user');
$spaceTable->deleted->range('0');
$spaceTable->gen(1);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 构造测试实例对象
$validInstance = new stdclass();
$validInstance->id = 1;
$validInstance->k8name = 'gitlab-test';
$validInstance->chart = 'gitlab';
$validInstance->channel = 'stable';
$validInstance->spaceData = new stdclass();
$validInstance->spaceData->k8space = 'quickon-user';

$instanceWithEmptyVersion = new stdclass();
$instanceWithEmptyVersion->id = 2;
$instanceWithEmptyVersion->k8name = 'subversion-test';
$instanceWithEmptyVersion->chart = 'subversion';
$instanceWithEmptyVersion->channel = 'stable';
$instanceWithEmptyVersion->spaceData = new stdclass();
$instanceWithEmptyVersion->spaceData->k8space = 'quickon-user';

$instanceWithEmptyAppVersion = new stdclass();
$instanceWithEmptyAppVersion->id = 3;
$instanceWithEmptyAppVersion->k8name = 'jenkins-test';
$instanceWithEmptyAppVersion->chart = 'jenkins';
$instanceWithEmptyAppVersion->channel = 'stable';
$instanceWithEmptyAppVersion->spaceData = new stdclass();
$instanceWithEmptyAppVersion->spaceData->k8space = 'quickon-user';

$instanceWithoutSpace = new stdclass();
$instanceWithoutSpace->id = 4;
$instanceWithoutSpace->k8name = 'nexus-test';
$instanceWithoutSpace->chart = 'nexus3';
$instanceWithoutSpace->channel = 'stable';
$instanceWithoutSpace->spaceData = new stdclass();
$instanceWithoutSpace->spaceData->k8space = 'quickon-user';

$sameVersionInstance = new stdclass();
$sameVersionInstance->id = 5;
$sameVersionInstance->k8name = 'sonar-test';
$sameVersionInstance->chart = 'sonarqube';
$sameVersionInstance->channel = 'stable';
$sameVersionInstance->spaceData = new stdclass();
$sameVersionInstance->spaceData->k8space = 'quickon-user';

r($instanceTest->upgradeTest($validInstance, '2024.01.1001', '16.0.0')) && p() && e('0'); // 步骤1：正常升级情况（CNE API不可用时返回0）
r($instanceTest->upgradeTest($instanceWithEmptyVersion, '', '1.15.0')) && p() && e('0'); // 步骤2：升级版本为空字符串
r($instanceTest->upgradeTest($instanceWithEmptyAppVersion, '2024.02.601', '')) && p() && e('0'); // 步骤3：应用版本为空字符串
r($instanceTest->upgradeTest($instanceWithoutSpace, '2024.03.801', '3.44.0')) && p() && e('0'); // 步骤4：实例对象缺少必要字段
r($instanceTest->upgradeTest($sameVersionInstance, '2024.03.901', '9.9')) && p() && e('0'); // 步骤5：升级到相同版本