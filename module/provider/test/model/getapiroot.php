#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('ops_provider')->loadYaml('provider', false, 2)->gen(5);

su('admin');

/**

title=测试 providerModel::getApiRoot();
timeout=0
cid=0

- 步骤1：GitLab 服务返回私有令牌接口地址 @https://alpha.example.com/api/v4%s?private_token=alpha-token
- 步骤2：GitHub 服务返回访问令牌接口地址 @https://bravo.example.com/api/v3%s?access_token=bravo-token
- 步骤3：Gitea 服务返回 token 接口地址 @https://charlie.example.com/api/v1%s?token=charlie-token
- 步骤4：Gogs 服务返回 token 接口地址 @https://delta.example.com/api/v1%s?token=delta-token
- 步骤5：Jenkins 服务返回空接口地址 @0
- 步骤6：不存在的服务返回空接口地址 @0
- 步骤7：ID 为 0 的服务返回空接口地址 @0

*/

$providerTester = new providerModelTest();
$gitLabID       = 1;
$gitHubID       = 2;
$giteaID        = 3;
$gogsID         = 4;
$jenkinsID      = 5;
$missingID      = 999;

r($providerTester->getApiRootTest($gitLabID)) && p() && e('https://alpha.example.com/api/v4%s?private_token=alpha-token');       // 步骤1：GitLab 服务返回私有令牌接口地址
r($providerTester->getApiRootTest($gitHubID)) && p() && e('https://bravo.example.com/api/v3%s?access_token=bravo-token');        // 步骤2：GitHub 服务返回访问令牌接口地址
r($providerTester->getApiRootTest($giteaID)) && p() && e('https://charlie.example.com/api/v1%s?token=charlie-token');            // 步骤3：Gitea 服务返回 token 接口地址
r($providerTester->getApiRootTest($gogsID)) && p() && e('https://delta.example.com/api/v1%s?token=delta-token');                 // 步骤4：Gogs 服务返回 token 接口地址
r(strlen($providerTester->getApiRootTest($jenkinsID))) && p() && e('0');                                                         // 步骤5：Jenkins 服务返回空接口地址
r(strlen($providerTester->getApiRootTest($missingID))) && p() && e('0');                                                         // 步骤6：不存在的服务返回空接口地址
r(strlen($providerTester->getApiRootTest(0))) && p() && e('0');                                                                  // 步骤7：ID 为 0 的服务返回空接口地址
