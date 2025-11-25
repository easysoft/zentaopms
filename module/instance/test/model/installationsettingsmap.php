#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::installationSettingsMap();
timeout=0
cid=16806

- 步骤1:自定义域名配置测试第ingress条的enabled属性 @1
- 步骤2:devops应用(gitea)配置测试第ci条的enabled属性 @1
- 步骤3:nexus3应用配置测试第ci条的enabled属性 @1
- 步骤4:自定义域名host字段测试第ingress条的host属性 @customhost.dev.haogs.cn
- 步骤5:sonarqube应用ingress配置测试第ingress条的enabled属性 @1
- 步骤6:sonarqube应用ci配置测试第ci条的enabled属性 @1
- 步骤7:gitlab应用ingress配置测试第ingress条的enabled属性 @1
- 步骤8:gitlab应用ci配置测试第ci条的enabled属性 @1
- 步骤9:jenkins应用ci配置测试第ci条的enabled属性 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$user = zenData('user');
$user->account->range('admin,user1,user2');
$user->password->range('123456{3}');
$user->role->range('admin,qa,dev');
$user->deleted->range('0{3}');
$user->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$instanceTest = new instanceModelTest();

// 准备测试数据
// 测试1: 自定义域名配置
$customData1 = new stdclass();
$customData1->customDomain = 'testapp';
$customData1->customName = 'Test App';
$customData1->dbType = 'unsharedDB';
$customData1->dbService = '';

$dbInfo1 = new stdclass();
$dbInfo1->name = 'mysql-service';
$dbInfo1->namespace = 'default';
$dbInfo1->host = 'mysql.default.svc';
$dbInfo1->port = 3306;

$instance1 = new stdclass();
$instance1->id = 1;
$instance1->chart = 'zentao';
$instance1->source = 'cloud';

// 测试2: devops应用配置
$customData2 = new stdclass();
$customData2->customDomain = '';
$customData2->customName = 'Gitea';
$customData2->dbType = 'unsharedDB';
$customData2->dbService = '';

$dbInfo2 = new stdclass();
$dbInfo2->name = 'mysql-service';
$dbInfo2->namespace = 'default';
$dbInfo2->host = 'mysql.default.svc';
$dbInfo2->port = 3306;

$instance2 = new stdclass();
$instance2->id = 2;
$instance2->chart = 'gitea';
$instance2->source = 'cloud';

// 测试3: 测试nexus3应用
$customData3 = new stdclass();
$customData3->customDomain = 'nexus';
$customData3->customName = 'Nexus3';
$customData3->dbType = 'unsharedDB';
$customData3->dbService = '';

$dbInfo3 = new stdclass();
$dbInfo3->name = 'mysql-service';
$dbInfo3->namespace = 'default';
$dbInfo3->host = 'mysql.default.svc';
$dbInfo3->port = 3306;

$instance3 = new stdclass();
$instance3->id = 3;
$instance3->chart = 'nexus3';
$instance3->source = 'cloud';

// 测试4: 测试自定义域名和ingress.host字段
$customData4 = new stdclass();
$customData4->customDomain = 'customhost';
$customData4->customName = 'Custom Host Test';
$customData4->dbType = 'unsharedDB';
$customData4->dbService = '';

$dbInfo4 = new stdclass();
$dbInfo4->name = 'mysql-service';
$dbInfo4->namespace = 'default';
$dbInfo4->host = 'mysql.default.svc';
$dbInfo4->port = 3306;

$instance4 = new stdclass();
$instance4->id = 4;
$instance4->chart = 'zentao';
$instance4->source = 'cloud';

// 测试5: 测试sonarqube应用
$customData5 = new stdclass();
$customData5->customDomain = 'sonar';
$customData5->customName = 'SonarQube';
$customData5->dbType = 'unsharedDB';
$customData5->dbService = '';

$dbInfo5 = new stdclass();
$dbInfo5->name = 'mysql-service';
$dbInfo5->namespace = 'default';
$dbInfo5->host = 'mysql.default.svc';
$dbInfo5->port = 3306;

$instance5 = new stdclass();
$instance5->id = 5;
$instance5->chart = 'sonarqube';
$instance5->source = 'cloud';

// 测试6: gitlab应用
$customData6 = new stdclass();
$customData6->customDomain = 'gitlab';
$customData6->customName = 'GitLab';
$customData6->dbType = 'unsharedDB';
$customData6->dbService = '';

$dbInfo6 = new stdclass();
$dbInfo6->name = 'mysql-service';
$dbInfo6->namespace = 'default';
$dbInfo6->host = 'mysql.default.svc';
$dbInfo6->port = 3306;

$instance6 = new stdclass();
$instance6->id = 6;
$instance6->chart = 'gitlab';
$instance6->source = 'cloud';

// 测试7: jenkins应用
$customData7 = new stdclass();
$customData7->customDomain = 'jenkins';
$customData7->customName = 'Jenkins';
$customData7->dbType = 'unsharedDB';
$customData7->dbService = '';

$dbInfo7 = new stdclass();
$dbInfo7->name = 'mysql-service';
$dbInfo7->namespace = 'default';
$dbInfo7->host = 'mysql.default.svc';
$dbInfo7->port = 3306;

$instance7 = new stdclass();
$instance7->id = 7;
$instance7->chart = 'jenkins';
$instance7->source = 'cloud';

// 5. 执行测试步骤
r($instanceTest->installationSettingsMapTest($customData1, $dbInfo1, $instance1)) && p('ingress:enabled') && e('1'); // 步骤1:自定义域名配置测试
r($instanceTest->installationSettingsMapTest($customData2, $dbInfo2, $instance2)) && p('ci:enabled') && e('1'); // 步骤2:devops应用(gitea)配置测试
r($instanceTest->installationSettingsMapTest($customData3, $dbInfo3, $instance3)) && p('ci:enabled') && e('1'); // 步骤3:nexus3应用配置测试
r($instanceTest->installationSettingsMapTest($customData4, $dbInfo4, $instance4)) && p('ingress:host') && e('customhost.dev.haogs.cn'); // 步骤4:自定义域名host字段测试
r($instanceTest->installationSettingsMapTest($customData5, $dbInfo5, $instance5)) && p('ingress:enabled') && e('1'); // 步骤5:sonarqube应用ingress配置测试
r($instanceTest->installationSettingsMapTest($customData5, $dbInfo5, $instance5)) && p('ci:enabled') && e('1'); // 步骤6:sonarqube应用ci配置测试
r($instanceTest->installationSettingsMapTest($customData6, $dbInfo6, $instance6)) && p('ingress:enabled') && e('1'); // 步骤7:gitlab应用ingress配置测试
r($instanceTest->installationSettingsMapTest($customData6, $dbInfo6, $instance6)) && p('ci:enabled') && e('1'); // 步骤8:gitlab应用ci配置测试
r($instanceTest->installationSettingsMapTest($customData7, $dbInfo7, $instance7)) && p('ci:enabled') && e('1'); // 步骤9:jenkins应用ci配置测试