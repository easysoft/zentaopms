#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::installationSettingsMap();
timeout=0
cid=0

- 步骤1:自定义域名非devops @1
- 步骤2:自定义域名devops @1
- 步骤3:空域名配置 @1
- 步骤4:共享数据库 @1
- 步骤5:nexus3应用 @1

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备(根据需要配置)
$userTable = zenData('user');
$userTable->account->range('admin,user1,user2');
$userTable->realname->range('管理员,用户1,用户2');
$userTable->password->range('123456{3}');
$userTable->deleted->range('0{3}');
$userTable->gen(3);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$instanceTest = new instanceModelTest();

// 准备测试数据
$customData1 = new stdClass();
$customData1->customDomain = 'test';
$customData1->customName = '测试应用';
$customData1->dbType = 'unsharedDB';
$customData1->dbService = '';

$dbInfo1 = new stdClass();
$dbInfo1->name = 'mysql-db';
$dbInfo1->namespace = 'default';
$dbInfo1->host = 'mysql.default.svc';
$dbInfo1->port = 3306;

$instance1 = new stdClass();
$instance1->id = 1;
$instance1->chart = 'zentao';
$instance1->source = 'cloud';

$customData2 = new stdClass();
$customData2->customDomain = 'gitlab-test';
$customData2->customName = 'GitLab测试';
$customData2->dbType = 'unsharedDB';
$customData2->dbService = '';

$instance2 = new stdClass();
$instance2->id = 2;
$instance2->chart = 'gitlab';
$instance2->source = 'cloud';

$customData3 = new stdClass();
$customData3->customDomain = 'jenkins-test';
$customData3->customName = 'Jenkins测试';
$customData3->dbType = 'unsharedDB';
$customData3->dbService = '';

$instance3 = new stdClass();
$instance3->id = 3;
$instance3->chart = 'jenkins';
$instance3->source = 'system';

$customData4 = new stdClass();
$customData4->customDomain = '';
$customData4->customName = '无域名应用';
$customData4->dbType = 'unsharedDB';
$customData4->dbService = '';

$instance4 = new stdClass();
$instance4->id = 4;
$instance4->chart = 'testapp';
$instance4->source = 'cloud';

$customData5 = new stdClass();
$customData5->customDomain = 'db-test';
$customData5->customName = '数据库测试';
$customData5->dbType = 'sharedDB';
$customData5->dbService = 'mysql-service';

$instance5 = new stdClass();
$instance5->id = 5;
$instance5->chart = 'zentao';
$instance5->source = 'cloud';

$customData6 = new stdClass();
$customData6->customDomain = 'normal-app';
$customData6->customName = '普通应用';
$customData6->dbType = 'unsharedDB';
$customData6->dbService = '';

$instance6 = new stdClass();
$instance6->id = 6;
$instance6->chart = 'normalapp';
$instance6->source = 'cloud';

$customData7 = new stdClass();
$customData7->customDomain = 'nexus-test';
$customData7->customName = 'Nexus测试';
$customData7->dbType = 'unsharedDB';
$customData7->dbService = '';

$instance7 = new stdClass();
$instance7->id = 7;
$instance7->chart = 'nexus3';
$instance7->source = 'cloud';

// 5. 强制要求:必须包含至少5个测试步骤
r(($result1 = $instanceTest->installationSettingsMapTest($customData1, $dbInfo1, $instance1)) && property_exists($result1, 'ingress') && !property_exists($result1, 'ci')) && p() && e('1'); // 步骤1:自定义域名非devops
r(($result2 = $instanceTest->installationSettingsMapTest($customData2, $dbInfo1, $instance2)) && property_exists($result2, 'ingress') && property_exists($result2, 'ci')) && p() && e('1'); // 步骤2:自定义域名devops
r(($result4 = $instanceTest->installationSettingsMapTest($customData4, $dbInfo1, $instance4)) && !property_exists($result4, 'ingress')) && p() && e('1'); // 步骤3:空域名配置
r(($result5 = $instanceTest->installationSettingsMapTest($customData5, $dbInfo1, $instance5)) && property_exists($result5, 'mysql') && $result5->mysql->enabled === false) && p() && e('1'); // 步骤4:共享数据库
r(($result7 = $instanceTest->installationSettingsMapTest($customData7, $dbInfo1, $instance7)) && property_exists($result7, 'ci') && $result7->ci->enabled) && p() && e('1'); // 步骤5:nexus3应用