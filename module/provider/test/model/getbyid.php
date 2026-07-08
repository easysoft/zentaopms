#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('ops_provider')->loadYaml('provider', false, 2)->gen(5);

su('admin');

/**

title=测试 providerModel::getByID();
timeout=0
cid=0

- 步骤1：获取 GitLab 服务时返回原始名称 @Alpha
- 步骤2：获取 GitLab 服务时账号字段为空 @1
- 步骤3：获取 Jenkins 服务时解码账号 @jenkins-admin
- 步骤4：获取 Jenkins 服务时解码令牌 @jenkins-token
- 步骤5：获取不存在的服务返回 false @0
- 步骤6：获取 ID 为 0 的服务返回 false @0

*/

$providerTester = new providerModelTest();
$gitlabID       = 1;
$jenkinsID      = 5;
$missingID      = 999;

r($providerTester->getByIDTest($gitlabID)) && p('name') && e('Alpha');                // 步骤1：获取 GitLab 服务时返回原始名称
r(empty($providerTester->getByIDTest($gitlabID)->account)) && p() && e('1');          // 步骤2：获取 GitLab 服务时账号字段为空
r($providerTester->getByIDTest($jenkinsID)) && p('account') && e('jenkins-admin');    // 步骤3：获取 Jenkins 服务时解码账号
r($providerTester->getByIDTest($jenkinsID)) && p('token') && e('jenkins-token');      // 步骤4：获取 Jenkins 服务时解码令牌
r($providerTester->getByIDTest($missingID)) && p() && e('0');                         // 步骤5：获取不存在的服务返回 false
r($providerTester->getByIDTest(0)) && p() && e('0');                                  // 步骤6：获取 ID 为 0 的服务返回 false
