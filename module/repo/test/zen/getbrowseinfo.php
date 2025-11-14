#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getBrowseInfo();
timeout=0
cid=18136

- 执行repoZenTest模块的getBrowseInfoTest方法，参数是$gitlabRepo 第0条的master属性 @master
- 执行repoZenTest模块的getBrowseInfoTest方法，参数是$gitRepo  @0
- 执行repoZenTest模块的getBrowseInfoTest方法，参数是$emptyRepo  @0
- 执行repoZenTest模块的getBrowseInfoTest方法，参数是$invalidRepo  @0
- 执行repoZenTest模块的getBrowseInfoTest方法，参数是$gitlabRepoEmpty 第1条的0属性 @v1.0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

zenData('repo');
$table = zenData('repo');
$table->id->range('1-5');
$table->SCM->range('Gitlab{1},Git{1},Subversion{1},Gitea{1},Gogs{1}');
$table->name->range('gitlab-repo,git-repo,svn-repo,gitea-repo,gogs-repo');
$table->serviceHost->range('1,2,3,4,5');
$table->serviceProject->range('10,20,30,40,50');
$table->path->range('/var/git/gitlab-repo,/var/git/git-repo,/var/svn/svn-repo,/var/git/gitea-repo,/var/git/gogs-repo');
$table->encoding->range('UTF-8{5}');
$table->client->range('git{3},svn{1},git{1}');
$table->account->range('admin{3},user{2}');
$table->password->range('123456{5}');
$table->gen(5);

su('admin');

$repoZenTest = new repoZenTest();

$gitlabRepo = new stdclass();
$gitlabRepo->SCM = 'Gitlab';
$gitlabRepo->serviceHost = 1;
$gitlabRepo->serviceProject = 10;
$gitlabRepo->path = '/var/git/gitlab-repo';
$gitlabRepo->encoding = 'UTF-8';
$gitlabRepo->client = 'git';
$gitlabRepo->account = 'admin';
$gitlabRepo->password = '123456';

$gitRepo = new stdclass();
$gitRepo->SCM = 'Git';

$emptyRepo = null;

$invalidRepo = 'not-an-object';

$gitlabRepoEmpty = new stdclass();
$gitlabRepoEmpty->SCM = 'Gitlab';
$gitlabRepoEmpty->serviceHost = 2;
$gitlabRepoEmpty->serviceProject = 20;
$gitlabRepoEmpty->path = '/var/git/gitlab-repo-empty';
$gitlabRepoEmpty->encoding = 'UTF-8';
$gitlabRepoEmpty->client = 'git';
$gitlabRepoEmpty->account = 'admin';
$gitlabRepoEmpty->password = '123456';

r($repoZenTest->getBrowseInfoTest($gitlabRepo)) && p('0:master') && e('master');
r($repoZenTest->getBrowseInfoTest($gitRepo)) && p() && e('0');
r($repoZenTest->getBrowseInfoTest($emptyRepo)) && p() && e('0');
r($repoZenTest->getBrowseInfoTest($invalidRepo)) && p() && e('0');
r($repoZenTest->getBrowseInfoTest($gitlabRepoEmpty)) && p('1:0') && e('v1.0');