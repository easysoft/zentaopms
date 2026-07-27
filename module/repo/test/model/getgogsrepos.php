#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

global $dbh, $tester;

/**
title=测试 repoModel->getGogsRepos();
timeout=0
cid=0

- 正常apiRoot返回项目列表 >> 1
- 返回的第一个项目有id属性 >> 1
- 返回的第一个项目有full_name属性 >> 1
- apiRoot为空字符串返回空数组 >> 1
- 调用方法返回值为array类型 >> 1
- 返回的项目数量 >= 2 >> 1

*/

$repoTest = new repoModelTest();

$mockProjects = array();
for($i = 1; $i <= 2; $i++)
{
    $project = new stdclass();
    $project->id        = $i + 300;
    $project->name      = "Gogs Project $i";
    $project->full_name = "gogs-user/gogs-project-$i";
    $mockProjects[] = $project;
}

$httpClient = $repoTest->resetHttpClient();
$httpClient->setResponse('/user/repos', json_encode($mockProjects));

r($repoTest->getGogsReposTest('https://gogs.example.com/api/v1/user/repos?token=testtoken')) && p('0:id,0:full_name') && e('301,gogs-user/gogs-project-1');
r($repoTest->getGogsReposTest('https://gogs.example.com/api/v1/user/repos?token=testtoken')) && p('1:id,1:full_name') && e('302,gogs-user/gogs-project-2');
r($repoTest->getGogsReposTest('https://gogs.example.com/api/v1/user/repos?token=testtoken')) && p('0:name') && e('Gogs Project 1');
r($repoTest->getGogsReposTest('')) && p() && e('0');
r($repoTest->getGogsReposTest('https://gogs.example.com/api/v1/user/repos?token=testtoken')) && p('0:id,1:id') && e('301,302');

$repoTest->restoreHttpClient();
