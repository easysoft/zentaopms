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
- 步骤2：GitHub 服务返回接口地址模板 @https://bravo.example.com%s
- 步骤3：GitHub 服务返回 Bearer 请求头 @Authorization: Bearer bravo-token
- 步骤4：Gitea 服务返回 token 接口地址 @https://charlie.example.com/api/v1%s?token=charlie-token
- 步骤5：Gogs 服务返回 token 接口地址 @https://delta.example.com/api/v1%s?token=delta-token
- 步骤6：Jenkins 服务返回空接口地址 @0
- 步骤7：空服务对象返回空接口地址 @0

*/

$providerTester  = new providerModelTest();
$gitLabProvider  = $providerTester->getByIDTest(1);
$gitHubProvider  = $providerTester->getByIDTest(2);
$giteaProvider   = $providerTester->getByIDTest(3);
$gogsProvider    = $providerTester->getByIDTest(4);
$jenkinsProvider = $providerTester->getByIDTest(5);
$emptyProvider   = new stdClass();

r($providerTester->getApiRootTest($gitLabProvider)) && p() && e('https://alpha.example.com/api/v4%s?private_token=alpha-token'); // 步骤1：GitLab 服务返回私有令牌接口地址
r($providerTester->getApiRootTest($gitHubProvider)) && p('url') && e('https://bravo.example.com%s');                             // 步骤2：GitHub 服务返回接口地址模板
r($providerTester->getApiRootTest($gitHubProvider)->header[0]) && p() && e('Authorization: Bearer bravo-token');                 // 步骤3：GitHub 服务返回 Bearer 请求头
r($providerTester->getApiRootTest($giteaProvider)) && p() && e('https://charlie.example.com/api/v1%s?token=charlie-token');     // 步骤4：Gitea 服务返回 token 接口地址
r($providerTester->getApiRootTest($gogsProvider)) && p() && e('https://delta.example.com/api/v1%s?token=delta-token');          // 步骤5：Gogs 服务返回 token 接口地址
r(strlen($providerTester->getApiRootTest($jenkinsProvider))) && p() && e('0');                                                   // 步骤6：Jenkins 服务返回空接口地址
r(strlen($providerTester->getApiRootTest($emptyProvider))) && p() && e('0');                                                     // 步骤7：空服务对象返回空接口地址
