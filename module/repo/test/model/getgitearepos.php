#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

global $tester;

/**
title=测试 repoModel->getGiteaRepos();
timeout=0
cid=0

- 正常apiRoot返回项目列表 >> 1
- 返回结果包含data字段 >> 1
- apiRoot为空字符串返回空数组 >> 1
- 调用方法返回值为array类型 >> 1
- 返回的项目数量 >= 2 >> 1

*/

$repoTest = new repoModelTest();

$mockResponse = new stdclass();
$mockResponse->ok   = true;
$mockResponse->data = array();
for($i = 1; $i <= 2; $i++)
{
    $project = new stdclass();
    $project->id        = $i + 200;
    $project->name      = "Gitea Project $i";
    $project->full_name = "gitea-user/gitea-project-$i";
    $mockResponse->data[] = $project;
}

$httpClient = $repoTest->resetHttpClient();
$httpClient->setResponse('/repos/search', json_encode($mockResponse));

$apiRoot = 'https://gitea.example.com/api/v1%s?token=testtoken';
r($repoTest->getGiteaReposTest($apiRoot)) && p('0:id')        && e('201');
r($repoTest->getGiteaReposTest($apiRoot)) && p('0:full_name') && e('gitea-user/gitea-project-1');
r($repoTest->getGiteaReposTest($apiRoot)) && p('1:id')        && e('202');
r($repoTest->getGiteaReposTest(''))       && p()              && e('0');
r($repoTest->getGiteaReposTest($apiRoot)) && p('0:name')      && e('Gitea Project 1');

$repoTest->restoreHttpClient();
