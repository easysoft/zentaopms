#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**
title=测试 repoModel->createRepo();
timeout=0
cid=18039

- 非法名称返回名称校验错误 @error,名称必须以字母或 _ 开头，只包含字母数字，连接符，下划线和点。
- 空名称返回名称校验错误 @error,名称必须以字母或 _ 开头，只包含字母数字，连接符，下划线和点。
- 数字开头名称返回名称校验错误 @error,名称必须以字母或 _ 开头，只包含字母数字，连接符，下划线和点。
- 小写合法名称调用真实 GitFox 接口属性name,status @validrepo,false
- 含连字符合法名称调用真实 GitFox 接口属性name,status @repo-valid-1,false

*/

$repoTest = new repoModelTest();

$_SERVER['REQUEST_URI'] = 'http://unittest/';
zenData('entry')->gen(0);

$entry = zenData('entry');
$entry->id->range('1');
$entry->name->range('GitFox');
$entry->account->range('');
$entry->code->range('gitfox');
$entry->key->range('cd65d97989fcb1fdb0d82471c3238a3a');
$entry->freePasswd->range('1');
$entry->ip->range('*');
$entry->createdBy->range('admin');
$entry->createdDate->range('2026-01-01 00:00:00');
$entry->calledTime->range('0');
$entry->editedBy->range('admin');
$entry->editedDate->range('2026-01-01 00:00:00');
$entry->deleted->range('0');
$entry->gen(1);
$repoTest->instance->config->devops->gitfoxURL  = 'http://localhost';
$repoTest->instance->config->devops->gitfoxPort = 3000;

$baseRepo = new stdclass();
$baseRepo->product  = '1';
$baseRepo->space    = 1;
$baseRepo->SCM      = 'Gitlab';
$baseRepo->acl      = 'open';
$baseRepo->desc     = 'repo unit test';

$repo1 = clone $baseRepo; $repo1->name = 'abc&&';
r($repoTest->createRepoTest($repo1)) && p('status,error') && e('error,名称必须以字母或 _ 开头，只包含字母数字，连接符，下划线和点。');

$repo2 = clone $baseRepo; $repo2->name = '';
r($repoTest->createRepoTest($repo2)) && p('status,error') && e('error,名称必须以字母或 _ 开头，只包含字母数字，连接符，下划线和点。');

$repo3 = clone $baseRepo; $repo3->name = '123invalid';
r($repoTest->createRepoTest($repo3)) && p('status,error') && e('error,名称必须以字母或 _ 开头，只包含字母数字，连接符，下划线和点。');

$repo4 = clone $baseRepo; $repo4->name = 'validrepo';
r($repoTest->createRepoTest($repo4)) && p('name,status') && e('validrepo,false');

$repo5 = clone $baseRepo; $repo5->name = 'repo-valid-1';
r($repoTest->createRepoTest($repo5)) && p('name,status') && e('repo-valid-1,false');
