#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getProviderRepos();
timeout=0
cid=0

- 空type provider 返回空数组 >> 1
- Gitlab类型provider 返回数组 >> 1
- showPairs=true 返回数组 >> 1
- 无效provider 返回数组 >> 1
- showPairs=true 再次调用 >> 1

*/

su('admin');

$repoTest = new repoModelTest();

$emptyProvider = new stdclass();
$emptyProvider->type = '';
r(is_array($repoTest->getProviderReposTest($emptyProvider))) && p() && e('1');

$gitlabProvider = new stdclass();
$gitlabProvider->type = 'Gitlab';
$gitlabProvider->id = 1;
$gitlabProvider->url = 'https://gitlab.example.com';
r(is_array($repoTest->getProviderReposTest($gitlabProvider))) && p() && e('1');

r(is_array($repoTest->getProviderReposTest($gitlabProvider, true))) && p() && e('1');

$invalidProvider = new stdclass();
$invalidProvider->type = 'Invalid';
r(is_array($repoTest->getProviderReposTest($invalidProvider))) && p() && e('1');

r(is_array($repoTest->getProviderReposTest($gitlabProvider, true))) && p() && e('1');
