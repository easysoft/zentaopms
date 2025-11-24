#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::installationSettingsMap();
timeout=0
cid=0

- 步骤1:不设置自定义域名且数据库类型为unsharedDB,返回stdClass对象 @stdClass
- 步骤2:设置自定义域名但数据库类型为unsharedDB第ingress条的enabled属性 @1
- 步骤3:设置自定义域名且使用共享数据库,检查ingress第ingress条的enabled属性 @1
- 步骤4:步骤3中的mysql配置检查,false输出为空第mysql条的enabled属性 @~~
- 步骤5:devops应用(gitlab)的安装配置,检查ci第ci条的enabled属性 @1
- 步骤6:数据库类型为空的边界情况第ingress条的enabled属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/instance.unittest.class.php';

zenData('user')->gen(10);

su('admin');

$instanceTest = new instanceTest();

// 准备测试数据
// 测试步骤1:不设置自定义域名且数据库类型为unsharedDB
$customData1 = new stdclass();
$customData1->customDomain = '';
$customData1->dbType = 'unsharedDB';
$customData1->dbService = '';

$dbInfo1 = new stdclass();
$dbInfo1->name = 'test-db';
$dbInfo1->namespace = 'default';
$dbInfo1->host = 'localhost';
$dbInfo1->port = 3306;

$instance1 = new stdclass();
$instance1->id = 1;
$instance1->chart = 'test-app';
$instance1->source = 'cloud';

// 测试步骤2:设置自定义域名但数据库类型为unsharedDB
$customData2 = new stdclass();
$customData2->customDomain = 'myapp';
$customData2->dbType = 'unsharedDB';
$customData2->dbService = '';

$dbInfo2 = new stdclass();
$dbInfo2->name = 'test-db';
$dbInfo2->namespace = 'default';
$dbInfo2->host = 'localhost';
$dbInfo2->port = 3306;

$instance2 = new stdclass();
$instance2->id = 2;
$instance2->chart = 'test-app';
$instance2->source = 'cloud';

// 测试步骤3:设置自定义域名且使用共享数据库
$customData3 = new stdclass();
$customData3->customDomain = 'myapp2';
$customData3->dbType = 'sharedDB';
$customData3->dbService = 'mysql-service';

$dbInfo3 = new stdclass();
$dbInfo3->name = 'shared-db';
$dbInfo3->namespace = 'database';
$dbInfo3->host = 'mysql.database.svc';
$dbInfo3->port = 3306;

$instance3 = new stdclass();
$instance3->id = 3;
$instance3->chart = 'test-app';
$instance3->source = 'cloud';

// 测试步骤4:devops应用(gitlab)的安装配置
$customData4 = new stdclass();
$customData4->customDomain = 'gitlab';
$customData4->dbType = 'unsharedDB';
$customData4->dbService = '';

$dbInfo4 = new stdclass();
$dbInfo4->name = 'gitlab-db';
$dbInfo4->namespace = 'default';
$dbInfo4->host = 'localhost';
$dbInfo4->port = 3306;

$instance4 = new stdclass();
$instance4->id = 4;
$instance4->chart = 'gitlab';
$instance4->source = 'cloud';

// 测试步骤5:系统来源且在initUserApps中的应用(zentao)
$customData5 = new stdclass();
$customData5->customDomain = 'zentao';
$customData5->dbType = 'unsharedDB';
$customData5->dbService = '';

$dbInfo5 = new stdclass();
$dbInfo5->name = 'zentao-db';
$dbInfo5->namespace = 'default';
$dbInfo5->host = 'localhost';
$dbInfo5->port = 3306;

$instance5 = new stdclass();
$instance5->id = 5;
$instance5->chart = 'zentao';
$instance5->source = 'system';

// 测试步骤6:数据库类型为空的边界情况
$customData6 = new stdclass();
$customData6->customDomain = 'test';
$customData6->dbType = '';
$customData6->dbService = '';

$dbInfo6 = new stdclass();
$dbInfo6->name = 'test-db';
$dbInfo6->namespace = 'default';
$dbInfo6->host = 'localhost';
$dbInfo6->port = 3306;

$instance6 = new stdclass();
$instance6->id = 6;
$instance6->chart = 'test-app';
$instance6->source = 'cloud';

// 执行测试
r(get_class($instanceTest->installationSettingsMapTest($customData1, $dbInfo1, $instance1))) && p() && e('stdClass'); // 步骤1:不设置自定义域名且数据库类型为unsharedDB,返回stdClass对象
r($instanceTest->installationSettingsMapTest($customData2, $dbInfo2, $instance2)) && p('ingress:enabled') && e('1'); // 步骤2:设置自定义域名但数据库类型为unsharedDB
r($result3 = $instanceTest->installationSettingsMapTest($customData3, $dbInfo3, $instance3)) && p('ingress:enabled') && e('1'); // 步骤3:设置自定义域名且使用共享数据库,检查ingress
r($result3) && p('mysql:enabled') && e('~~'); // 步骤4:步骤3中的mysql配置检查,false输出为空
r($instanceTest->installationSettingsMapTest($customData4, $dbInfo4, $instance4)) && p('ci:enabled') && e('1'); // 步骤5:devops应用(gitlab)的安装配置,检查ci
r($instanceTest->installationSettingsMapTest($customData6, $dbInfo6, $instance6)) && p('ingress:enabled') && e('1'); // 步骤6:数据库类型为空的边界情况