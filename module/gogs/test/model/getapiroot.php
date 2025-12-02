#!/usr/bin/env php
<?php

/**

title=测试 gogsModel::getApiRoot();
timeout=0
cid=16692

- 步骤1：不存在的服务器ID @0
- 步骤2：非gogs类型的服务器ID（gitlab） @0
- 步骤3：正确的gogs服务器ID @https://gogs1.example.com/api/v1%s?token=token3
- 步骤4：零值ID @0
- 步骤5：负数ID @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gogs.unittest.class.php';

// 准备测试数据
$table = zenData('pipeline');
$table->id->range('1-10');
$table->type->range('gitlab,jenkins,gogs{3},gitea{3},sonarqube{2}');
$table->name->range('GitLab服务器,Jenkins服务器,Gogs服务器1,Gogs服务器2,Gogs服务器3,Gitea服务器1,Gitea服务器2,Gitea服务器3,SonarQube服务器1,SonarQube服务器2');
$table->url->range('[https://gitlab.example.com],[https://jenkins.example.com],[https://gogs1.example.com],[https://gogs2.example.com],[https://gogs3.example.com],[https://gitea1.example.com],[https://gitea2.example.com],[https://gitea3.example.com],[https://sonar1.example.com],[https://sonar2.example.com]');
$table->token->range('token1,token2,token3,token4,token5,token6,token7,token8,token9,token10');
$table->gen(10);

su('admin');

$gogsTest = new gogsTest();

r($gogsTest->getApiRootTest(999)) && p() && e('0'); // 步骤1：不存在的服务器ID
r($gogsTest->getApiRootTest(1)) && p() && e('0'); // 步骤2：非gogs类型的服务器ID（gitlab）
r($gogsTest->getApiRootTest(3)) && p() && e('https://gogs1.example.com/api/v1%s?token=token3'); // 步骤3：正确的gogs服务器ID
r($gogsTest->getApiRootTest(0)) && p() && e('0'); // 步骤4：零值ID
r($gogsTest->getApiRootTest(-1)) && p() && e('0'); // 步骤5：负数ID