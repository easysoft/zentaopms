#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::create();
timeout=0
cid=17342

- 执行pipelineTester模块的createTest方法，参数是'jenkins', $jenkinsData 属性type @jenkins
- 执行pipelineTester模块的createTest方法，参数是'gitlab', $gitlabData 属性account @gitlab_admin
- 执行pipelineTester模块的createTest方法，参数是'jenkins', $invalidUrlData 第url条的0属性 @『服务器地址』应当为合法的URL。
- 执行pipelineTester模块的createTest方法，参数是'jenkins', $emptyNameData 第name条的0属性 @『应用名称』不能为空。
- 执行pipelineTester模块的createTest方法，参数是'jenkins', $emptyAccountData 第account条的0属性 @『用户名』不能为空。
- 执行pipelineTester模块的createTest方法，参数是'jenkins', $duplicateNameData 第name条的0属性 @『应用名称』已经有『ExistingJenkins』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
- 执行pipelineTester模块的createTest方法，参数是'jenkins', $emptyAuthData 第password条的0属性 @『密码』不能为空。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$pipelineTable = zenData('pipeline');
$pipelineTable->id->range('1-5');
$pipelineTable->name->range('ExistingJenkins,ExistingGitlab,ExistingPipeline3,ExistingPipeline4,ExistingPipeline5');
$pipelineTable->type->range('jenkins{2},gitlab{2},gitea');
$pipelineTable->url->range('http://jenkins.example.com,http://gitlab.example.com,http://test1.com,http://test2.com,http://test3.com');
$pipelineTable->account->range('admin,gitlab_admin,test1,test2,test3');
$pipelineTable->deleted->range('0');
$pipelineTable->gen(5);

zenData('user')->gen(5);

su('admin');

$pipelineTester = new pipelineModelTest();

$jenkinsData = new stdClass();
$jenkinsData->name = 'TestJenkins';
$jenkinsData->url = 'http://jenkins.test.com/';
$jenkinsData->account = 'jenkins_admin';
$jenkinsData->token = 'jenkins_token_123';
$jenkinsData->password = 'jenkins_pass_123';

$gitlabData = new stdClass();
$gitlabData->name = 'TestGitlab';
$gitlabData->url = 'http://gitlab.test.com/';
$gitlabData->account = 'gitlab_admin';
$gitlabData->token = 'gitlab_token_123';

$invalidUrlData = new stdClass();
$invalidUrlData->name = 'TestInvalidUrl';
$invalidUrlData->url = 'invalid-url-format';
$invalidUrlData->account = 'test_account';
$invalidUrlData->token = 'test_token';
$invalidUrlData->password = '';

$emptyNameData = new stdClass();
$emptyNameData->name = '';
$emptyNameData->url = 'http://test.example.com/';
$emptyNameData->account = 'test_account';
$emptyNameData->token = 'test_token';
$emptyNameData->password = '';

$emptyAccountData = new stdClass();
$emptyAccountData->name = 'TestJenkinsEmptyAccount';
$emptyAccountData->url = 'http://jenkins2.test.com/';
$emptyAccountData->account = '';
$emptyAccountData->token = 'test_token';
$emptyAccountData->password = '';

$duplicateNameData = new stdClass();
$duplicateNameData->name = 'ExistingJenkins';
$duplicateNameData->url = 'http://jenkins3.test.com/';
$duplicateNameData->account = 'test_account';
$duplicateNameData->token = 'test_token';
$duplicateNameData->password = '';

$emptyAuthData = new stdClass();
$emptyAuthData->name = 'TestJenkinsEmptyAuth';
$emptyAuthData->url = 'http://jenkins4.test.com/';
$emptyAuthData->account = 'test_account';
$emptyAuthData->token = '';
$emptyAuthData->password = '';

r($pipelineTester->createTest('jenkins', $jenkinsData)) && p('type') && e('jenkins');
r($pipelineTester->createTest('gitlab', $gitlabData)) && p('account') && e('gitlab_admin');
r($pipelineTester->createTest('jenkins', $invalidUrlData)) && p('url:0') && e('『服务器地址』应当为合法的URL。');
r($pipelineTester->createTest('jenkins', $emptyNameData)) && p('name:0') && e('『应用名称』不能为空。');
r($pipelineTester->createTest('jenkins', $emptyAccountData)) && p('account:0') && e('『用户名』不能为空。');
r($pipelineTester->createTest('jenkins', $duplicateNameData)) && p('name:0') && e('『应用名称』已经有『ExistingJenkins』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。');
r($pipelineTester->createTest('jenkins', $emptyAuthData)) && p('password:0') && e('『密码』不能为空。');