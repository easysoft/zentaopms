#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getRepoGroup();
timeout=0
cid=1

- 按项目分组第4条的text属性 @正常产品4
- 按项目分组对应的repo第0条的text属性 @testHtml
- 指定projectID获取项目分组 @0
- 指定存在的projectID获取项目分组 @1
- 指定repoType获取项目分组个数 @3

*/

zdTable('product')->gen(10);
zdTable('project')->gen(20);
zdTable('projectproduct')->gen(20);
zdTable('repo')->config('repo')->gen(4);

$repo = $tester->loadModel('repo');

$type      = 'project';
$projectID = 1;
$repoType  = 'git';

$result = $repo->getRepoGroup($type);
r($result)             && p('4:text') && e('正常产品4'); //按项目分组
r($result[1]['items']) && p('0:text') && e('testHtml'); //按项目分组对应的repo

$result = $repo->getRepoGroup($type, $projectID);
r(count($result)) && p() && e(0); //指定projectID获取项目分组

$projectID = 11;
$result    = $repo->getRepoGroup($type, $projectID);
r(count($result)) && p() && e(1); //指定存在的projectID获取项目分组

$result = $repo->getRepoGroup($type, 0, $repoType);
r(count($result)) && p() && e(3); //指定repoType获取项目分组个数