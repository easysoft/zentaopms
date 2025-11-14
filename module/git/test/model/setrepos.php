#!/usr/bin/env php
<?php

/**

title=测试 gitModel::setRepos();
timeout=0
cid=16556

- 执行gitTest模块的setReposTest方法 属性result @1
- 执行gitTest模块的setReposTest方法 属性count @6
- 执行gitTest模块的setReposTest方法 属性firstSCM @Git
- 执行gitTest模块的setReposTest方法 属性hasAcl @not_exists
- 执行gitTest模块的setReposTest方法 属性hasDesc @not_exists
- 执行gitTest模块的setReposTest方法 属性output @You must set one git repo.

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/git.unittest.class.php';

su('admin');

$gitTest = new gitTest();

$repo = zenData('repo');
$repo->id->range('1-8');
$repo->product->range('1{8}');
$repo->name->range('testrepo1,testrepo2,testrepo3,testrepo4,svnrepo1,hgrepo1,testrepo5,testrepo6');
$repo->path->range('/path/to/repo1,/path/to/repo2,/path/to/repo1,/path/to/repo4,/path/to/svnrepo,/path/to/hgrepo,/path/to/repo5,/path/to/repo6');
$repo->SCM->range('Git,Gitlab,Gogs,Gitea,SVN,Mercurial,Git,Gitlab');
$repo->client->range('1{8}');
$repo->deleted->range('0{8}');
$repo->synced->range('1{8}');
$repo->acl->range('open{8}');
$repo->desc->range('Test description{8}');
$repo->gen(8);

r($gitTest->setReposTest()) && p('result') && e('1');
r($gitTest->setReposTest()) && p('count') && e('6');
r($gitTest->setReposTest()) && p('firstSCM') && e('Git');
r($gitTest->setReposTest()) && p('hasAcl') && e('not_exists');
r($gitTest->setReposTest()) && p('hasDesc') && e('not_exists');

zenData('repo')->gen(0);

r($gitTest->setReposTest()) && p('output') && e('You must set one git repo.');