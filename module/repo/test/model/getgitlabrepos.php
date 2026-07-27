#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**
title=测试 repoModel->getGitLabRepos();
timeout=0
cid=0

- 正常apiRoot返回项目列表 >> 1
- 返回数组的第一个元素有id属性 >> 1
- 返回数组的第一个元素有name属性 >> 1
- apiRoot为空字符串返回空数组 >> 1
- 调用方法返回值为array类型 >> 1
- 返回的项目数量 >= 1 >> 1

*/

$repoTest = new repoModelTest();

$mockProjects = array();
for($i = 1; $i <= 3; $i++)
{
    $project = new stdclass();
    $project->id = $i + 100;
    $project->name = "Test Project $i";
    $project->path = "test-project-$i";
    $project->path_with_namespace = "test-group/test-project-$i";
    $mockProjects[] = $project;
}

$httpClient = $repoTest->resetHttpClient();
$httpClient->setResponse('/projects', json_encode($mockProjects));

r($repoTest->getGitLabReposTest('https://gitlab.example.com/api/v4/projects?private_token=testtoken')) && p('0:id,0:name,1:name') && e('101,Test Project 1,Test Project 2');
r($repoTest->getGitLabReposTest('https://gitlab.example.com/api/v4/projects?private_token=testtoken')) && p('0:path_with_namespace') && e('test-group/test-project-1');
r($repoTest->getGitLabReposTest('https://gitlab.example.com/api/v4/projects?private_token=testtoken')) && p('2:id,2:path') && e('103,test-project-3');
r($repoTest->getGitLabReposTest('')) && p() && e('0');
r($repoTest->getGitLabReposTest('https://gitlab.example.com/api/v4/projects?private_token=testtoken')) && p('0:id,1:id,2:id') && e('101,102,103');

$repoTest->restoreHttpClient();
