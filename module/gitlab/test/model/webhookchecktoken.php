#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::webhookCheckToken();
timeout=0
cid=16670

- 执行gitlabTest模块的webhookCheckTokenTest方法  @Token error.
- 执行gitlabTest模块的webhookCheckTokenTest方法  @Token error.
- 执行gitlabTest模块的webhookCheckTokenTest方法  @0
- 执行gitlabTest模块的webhookCheckTokenTest方法  @Token error.
- 执行gitlabTest模块的webhookCheckTokenTest方法  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

// 准备测试数据
$table = zenData('pipeline');
$table->id->range('1-5');
$table->type->range('gitlab{5}');
$table->name->range('GitLab1,GitLab2,GitLab3,GitLab4,GitLab5');
$table->url->range('https://gitlab.example.com{5}');
$table->private->range('08bcc98f75d7d40053dc80722bdc117b,token123,validtoken456,specialtoken789,emptytoken');
$table->deleted->range('0{5}');
$table->gen(5);

// 用户登录
su('admin');

// 创建测试实例
$gitlabTest = new gitlabTest();

// 测试步骤1：使用空token验证
$_GET['gitlab'] = 1;
$_SERVER['HTTP_X_GITLAB_TOKEN'] = '';
r($gitlabTest->webhookCheckTokenTest()) && p() && e('Token error.');

// 测试步骤2：使用错误的token验证
$_GET['gitlab'] = 1;
$_SERVER['HTTP_X_GITLAB_TOKEN'] = 'wrongtoken123';
r($gitlabTest->webhookCheckTokenTest()) && p() && e('Token error.');

// 测试步骤3：使用正确的token验证
$_GET['gitlab'] = 1;
$_SERVER['HTTP_X_GITLAB_TOKEN'] = '08bcc98f75d7d40053dc80722bdc117b';
r($gitlabTest->webhookCheckTokenTest()) && p() && e('0');

// 测试步骤4：测试第二个gitlab实例的token不匹配
$_GET['gitlab'] = 2;
$_SERVER['HTTP_X_GITLAB_TOKEN'] = 'wrongtoken456';
r($gitlabTest->webhookCheckTokenTest()) && p() && e('Token error.');

// 测试步骤5：测试第二个gitlab实例的token匹配
$_GET['gitlab'] = 2;
$_SERVER['HTTP_X_GITLAB_TOKEN'] = 'token123';
r($gitlabTest->webhookCheckTokenTest()) && p() && e('0');