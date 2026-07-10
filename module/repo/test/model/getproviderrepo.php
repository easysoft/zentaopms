#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->getProviderRepo();
timeout=0
cid=18120

- 测试 getProviderRepo 传入空 provider type 返回 false @0
- 测试 getProviderRepo 传入未知 provider type 返回 false @0
- 测试 getProviderRepo 传入小写 gitlab type 返回 false @0
- 测试 getProviderRepo 传入 Subversion type 返回 false @0
- 测试 getProviderRepo 传入前后空格 type 返回 false @0
*/

$repo = new repoModelTest();

$provider = new stdclass();
$provider->type = '';

r($repo->getProviderRepoTest($provider, '123')) && p() && e('0');

$provider = new stdclass();
$provider->type  = 'Unknown';
$provider->url   = 'https://example.com';
$provider->token = 'token';

r($repo->getProviderRepoTest($provider, '123')) && p() && e('0');

$provider = new stdclass();
$provider->type  = 'gitlab';
$provider->url   = 'https://example.com';
$provider->token = 'token';

r($repo->getProviderRepoTest($provider, '123')) && p() && e('0');

$provider = new stdclass();
$provider->type  = 'Subversion';
$provider->url   = 'https://example.com';
$provider->token = 'token';

r($repo->getProviderRepoTest($provider, '123')) && p() && e('0');

$provider = new stdclass();
$provider->type  = ' GitLab ';
$provider->url   = 'https://example.com';
$provider->token = 'token';

r($repo->getProviderRepoTest($provider, '123')) && p() && e('0');
