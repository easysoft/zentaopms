#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('ops_provider')->gen(0);

su('admin');

/**

title=测试 providerModel::create();
timeout=0
cid=0

- 步骤1：创建 GitLab 服务后保存类型 @GitLab
- 步骤2：创建 GitLab 服务后保存地址 @https://gitlab.example.com
- 步骤3：创建 Jenkins 服务后保存令牌 @jenkins-token
- 步骤4：服务名称为空时，返回名称必填错误 @『服务名称』不能为空。
- 步骤5：服务地址为空时，返回地址必填错误 @『服务器地址』不能为空。
- 步骤6：创建 Subversion 服务时，保存服务类型成功 @Subversion
- 步骤7：创建 Subversion 服务时，允许空令牌 @~~

*/

$providerTester = new providerModelTest();

$gitlabProvider = array('type' => 'GitLab', 'name' => 'GitLab服务', 'url' => 'https://gitlab.example.com', 'token' => 'secret', 'createdBy' => 'admin');
$jenkinsProvider = array('type' => 'Jenkins', 'name' => 'Jenkins服务', 'url' => 'https://jenkins.example.com', 'token' => 'jenkins-token', 'createdBy' => 'admin');
$emptyNameProvider = array('type' => 'GitLab', 'name' => '', 'url' => 'https://empty-name.example.com', 'token' => 'secret', 'createdBy' => 'admin');
$emptyUrlProvider = array('type' => 'GitLab', 'name' => '空地址服务', 'url' => '', 'token' => 'secret', 'createdBy' => 'admin');
$subversionProvider = array('type' => 'Subversion', 'name' => 'Subversion服务', 'url' => 'svn://svn.example.com/repo', 'token' => '', 'createdBy' => 'admin');

r($providerTester->createTest($gitlabProvider)) && p('type') && e('GitLab');                             // 步骤1：创建 GitLab 服务后保存类型
r($providerTester->createTest($gitlabProvider)) && p('url') && e('https://gitlab.example.com');         // 步骤2：创建 GitLab 服务后保存地址
r($providerTester->createTest($jenkinsProvider)) && p('token') && e('jenkins-token');                   // 步骤3：创建 Jenkins 服务后保存令牌
r($providerTester->createTest($emptyNameProvider)) && p('name:0') && e('『服务名称』不能为空。');        // 步骤4：服务名称为空时，返回名称必填错误
r($providerTester->createTest($emptyUrlProvider)) && p('url:0') && e('『服务器地址』不能为空。');         // 步骤5：服务地址为空时，返回地址必填错误
r($providerTester->createTest($subversionProvider)) && p('type') && e('Subversion');                    // 步骤6：创建 Subversion 服务时，保存服务类型成功
r($providerTester->createTest($subversionProvider)) && p('token') && e('~~');                            // 步骤7：创建 Subversion 服务时，允许空令牌
