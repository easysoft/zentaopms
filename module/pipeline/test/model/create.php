#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/job.unittest.class.php';
su('admin');

/**

title=jobModel->create();
timeout=0
cid=16837

- 测试创建job name为空的情况第name条的0属性 @『流水线名称』不能为空。
- 测试创建job engine为空的情况第engine条的0属性 @『引擎』不能为空。
- 测试创建job name为《这是一个job007》的情况属性name @这是一个job007
- 测试创建job engine为gitlab的情况属性engine @gitlab
- 测试创建job triggerType为tag的情况属性triggerType @tag

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$job_name              = array('name' => '这是一个job007');
$job_engine            = array('engine' => 'gitlab');
$job_triggerType       = array('triggerType' => 'tag');
$job_name_blank        = array('name' => '');
$job_engine_blank      = array('engine' => '');
$job_triggerType_blank = array('triggerType' => '');

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
