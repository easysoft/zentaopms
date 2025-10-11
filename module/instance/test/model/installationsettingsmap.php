#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::installationSettingsMap();
timeout=0
cid=0

- 执行instanceTest模块的installationSettingsMapTest方法 第ingress条的enabled属性 @1
- 执行instanceTest模块的installationSettingsMapTest方法 第ci条的enabled属性 @1
- 执行instanceTest模块的installationSettingsMapTest方法 属性auth @~~
- 执行$result4->mysql @1
- 执行instanceTest模块的installationSettingsMapTest方法  @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/instance.unittest.class.php';

// 2. 模拟配置
global $config;
$config->instance = new stdclass;
$config->instance->devopsApps = array('gitea', 'gitlab', 'jenkins', 'sonarqube', 'nexus3', 'nexus');
$config->instance->initUserApps = array('zentao');

// 3. 跳过zendata，直接测试

// 4. 用户登录（选择合适角色）
su('admin');

// 5. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceTest();

// 6. 测试步骤
r($instanceTest->installationSettingsMapTest((object)array('customDomain' => 'test', 'dbType' => 'unsharedDB'), new stdclass, (object)array('chart' => 'zentao', 'source' => 'cloud'))) && p('ingress:enabled') && e('1');
r($instanceTest->installationSettingsMapTest((object)array('customDomain' => '', 'dbType' => 'unsharedDB'), new stdclass, (object)array('chart' => 'gitea', 'source' => 'cloud'))) && p('ci:enabled') && e('1');
r($instanceTest->installationSettingsMapTest((object)array('customDomain' => '', 'dbType' => 'unsharedDB'), new stdclass, (object)array('chart' => 'zentao', 'source' => 'system'))) && p('auth') && e('~~');
$result4 = $instanceTest->installationSettingsMapTest((object)array('customDomain' => '', 'dbType' => 'sharedDB', 'dbService' => 'mysql-service'), (object)array('name' => 'mysql-service', 'namespace' => 'default', 'host' => 'mysql.default.svc.cluster.local', 'port' => '3306'), (object)array('chart' => 'zentao', 'source' => 'cloud', 'id' => 1));
r(isset($result4->mysql)) && p() && e('1');
r(!empty($instanceTest->installationSettingsMapTest((object)array('customDomain' => '', 'dbType' => ''), new stdclass, (object)array('chart' => 'zentao', 'source' => 'cloud')))) && p() && e('1');