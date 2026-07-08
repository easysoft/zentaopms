#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('ops_provider')->loadYaml('provider', false, 2)->gen(5);

su('admin');

/**

title=测试 providerModel::update();
timeout=0
cid=0

- 步骤1：更新 GitLab 服务名称成功 @更新后的GitLab服务
- 步骤2：更新 GitLab 服务名称时保留原服务类型 @GitLab
- 步骤3：更新 GitLab 服务地址成功 @https://updated.example.com
- 步骤4：更新 GitLab 服务令牌为空时保存空值 @~~
- 步骤5：更新服务名称为空时返回名称必填错误 @『服务名称』不能为空。
- 步骤6：更新服务地址为空时返回地址必填错误 @『服务器地址』不能为空。

*/

$providerTester = new providerModelTest();
$gitlabID       = 1;

r($providerTester->updateTest($gitlabID, array('name' => '更新后的GitLab服务'))) && p('name') && e('更新后的GitLab服务');                 // 步骤1：更新 GitLab 服务名称成功
r($providerTester->updateTest($gitlabID, array('name' => '再次更新GitLab服务'))) && p('type') && e('GitLab');                            // 步骤2：更新 GitLab 服务名称时保留原服务类型
r($providerTester->updateTest($gitlabID, array('url' => 'https://updated.example.com'))) && p('url') && e('https://updated.example.com'); // 步骤3：更新 GitLab 服务地址成功
r($providerTester->updateTest($gitlabID, array('token' => ''))) && p('token') && e('~~');                                                 // 步骤4：更新 GitLab 服务令牌为空时保存空值
r($providerTester->updateTest($gitlabID, array('name' => ''))) && p('name:0') && e('『服务名称』不能为空。');                              // 步骤5：更新服务名称为空时返回名称必填错误
r($providerTester->updateTest($gitlabID, array('url' => ''))) && p('url:0') && e('『服务器地址』不能为空。');                               // 步骤6：更新服务地址为空时返回地址必填错误
