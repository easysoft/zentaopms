#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::installationSettingsMap();
timeout=0
cid=0

- 执行$result第ingress条的enabled属性 @1
- 执行$result第ci条的enabled属性 @1
- 执行$result->ci @1
- 执行$result->mysql @1
- 执行$result @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/instance.unittest.class.php';

// 2. zendata数据准备
$userTable = zenData('user');
$userTable->account->range('admin,user1,user2');
$userTable->realname->range('管理员,用户1,用户2');
$userTable->deleted->range('0{3}');
$userTable->gen(3);

$instanceTable = zenData('instance');
$instanceTable->id->range('1-10');
$instanceTable->chart->range('zentao{2},gitea{2},gitlab{2},jenkins{2},sonarqube{2}');
$instanceTable->source->range('system{5},cloud{5}');
$instanceTable->deleted->range('0{10}');
$instanceTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 步骤1：测试自定义域名配置 >> 期望配置ingress
$customData = new stdclass;
$customData->customDomain = 'test';
$customData->dbType = 'unsharedDB';
$dbInfo = new stdclass;
$instance = new stdclass;
$instance->chart = 'zentao';
$instance->source = 'cloud';
$result = $instanceTest->installationSettingsMapTest($customData, $dbInfo, $instance);
r($result) && p('ingress:enabled') && e('1');

// 步骤2：测试devops应用CI配置 >> 期望启用CI
$customData = new stdclass;
$customData->customDomain = '';
$customData->dbType = 'unsharedDB';
$dbInfo = new stdclass;
$instance = new stdclass;
$instance->chart = 'gitea';
$instance->source = 'cloud';
$result = $instanceTest->installationSettingsMapTest($customData, $dbInfo, $instance);
r($result) && p('ci:enabled') && e('1');

// 步骤3：测试非devops应用配置 >> 期望没有CI配置
$customData = new stdclass;
$customData->customDomain = '';
$customData->dbType = 'unsharedDB';
$dbInfo = new stdclass;
$instance = new stdclass;
$instance->chart = 'zentao';
$instance->source = 'system';
$result = $instanceTest->installationSettingsMapTest($customData, $dbInfo, $instance);
r(!isset($result->ci)) && p() && e('1');

// 步骤4：测试数据库配置 >> 期望设置MySQL配置
$customData = new stdclass;
$customData->customDomain = '';
$customData->dbType = 'sharedDB';
$customData->dbService = 'mysql-service';
$dbInfo = new stdclass;
$dbInfo->name = 'mysql-service';
$dbInfo->namespace = 'default';
$dbInfo->host = 'mysql.default.svc.cluster.local';
$dbInfo->port = '3306';
$instance = new stdclass;
$instance->chart = 'zentao';
$instance->source = 'cloud';
$instance->id = 1;
$result = $instanceTest->installationSettingsMapTest($customData, $dbInfo, $instance);
r(isset($result->mysql)) && p() && e('1');

// 步骤5：测试空数据库类型配置 >> 期望返回基本配置
$customData = new stdclass;
$customData->customDomain = '';
$customData->dbType = '';
$dbInfo = new stdclass;
$instance = new stdclass;
$instance->chart = 'zentao';
$instance->source = 'cloud';
$result = $instanceTest->installationSettingsMapTest($customData, $dbInfo, $instance);
r(!empty($result)) && p() && e('1');