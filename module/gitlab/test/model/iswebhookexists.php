#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::isWebhookExists();
timeout=0
cid=0

- 执行gitlabTest模块的isWebhookExistsTest方法，参数是1, 'http://api.php/v1/gitlab/webhook?repoID=1'  @1
- 执行gitlabTest模块的isWebhookExistsTest方法，参数是1, 'http://api.php/v1/gitlab/webhook?repoID=999'  @0
- 执行gitlabTest模块的isWebhookExistsTest方法，参数是1, ''  @0
- 执行gitlabTest模块的isWebhookExistsTest方法，参数是999, 'http://api.php/v1/gitlab/webhook?repoID=1'  @0
- 执行gitlabTest模块的isWebhookExistsTest方法，参数是1, 'http://api.php/v1/gitlab/webhook?repoID=1&param=test%20value'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

// 准备测试数据
$table = zenData('pipeline');
$table->id->range('1-10');
$table->type->range('gitlab{10}');
$table->name->range('gitlab-test{5},gitlab-prod{3},gitlab-dev{2}');
$table->url->range('http://gitlab.test.com{5},http://gitlab.prod.com{3},http://gitlab.dev.com{2}');
$table->account->range('admin{3},user{4},dev{3}');
$table->deleted->range('0{8},1{2}');
$table->gen(10);

$repo = zenData('repo');
$repo->id->range('1-3');
$repo->product->range('1{3}');
$repo->name->range('test-repo{3}');
$repo->path->range('42{3}');
$repo->SCM->range('Gitlab{3}');
$repo->client->range('1{3}');
$repo->serviceHost->range('1{3}');
$repo->serviceProject->range('42{3}');
$repo->extra->range('42{3}');
$repo->preMerge->range('1{3}');
$repo->job->range('1{3}');
$repo->deleted->range('0{3}');
$repo->gen(3);

su('admin');

$gitlabTest = new gitlabTest();

r($gitlabTest->isWebhookExistsTest(1, 'http://api.php/v1/gitlab/webhook?repoID=1')) && p() && e('1');
r($gitlabTest->isWebhookExistsTest(1, 'http://api.php/v1/gitlab/webhook?repoID=999')) && p() && e('0');
r($gitlabTest->isWebhookExistsTest(1, '')) && p() && e('0');
r($gitlabTest->isWebhookExistsTest(999, 'http://api.php/v1/gitlab/webhook?repoID=1')) && p() && e('0');
r($gitlabTest->isWebhookExistsTest(1, 'http://api.php/v1/gitlab/webhook?repoID=1&param=test%20value')) && p() && e('0');