#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getFileCommits();
timeout=0
cid=8

- 获取代码文件得提交信息
 - 第0条的revision属性 @c808480afe22d3a55d94e91c59a8f3170212ade0
 - 第0条的date属性 @2023-12-13 19:00:25
- 获取操作为删除文件得提交信息 @0
- 获取svn代码库得提交信息
 - 第0条的revision属性 @1
 - 第0条的comment属性 @+ Add file.

*/

zdTable('pipeline')->gen(4);
zdTable('repo')->config('repo')->gen(5);
zdTable('repohistory')->config('repohistory')->gen(4);
$repoBranch = zdTable('repobranch');
$repoBranch->branch->range('branch3');
$repoBranch->gen(1);
zdTable('repofiles')->gen(10);

$repo = new repoTest();

$gitlabID = 1;
$giteaID  = 3;
$branch   = 'branch3';
$parent   = '/trunk/zentaoext/zentaopro/cmmi/db';

r($repo->getFileCommitsTest($gitlabID, $branch)) && p('0:revision,date')    && e('c808480afe22d3a55d94e91c59a8f3170212ade0,2023-12-13 19:00:25'); //获取代码文件得提交信息
r($repo->getFileCommitsTest($giteaID, ''))       && p()                     && e('0'); //获取操作为删除文件得提交信息
r($repo->getFileCommitsTest(4, '', $parent))     && p('0:revision,comment') && e('1,+ Add file.'); //获取svn代码库得提交信息
