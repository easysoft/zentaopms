#!/usr/bin/env php
<?php

/**

title=测试 jobZen::getSubversionDir();
timeout=0
cid=16864

- 执行jobTest模块的getSubversionDirTest方法，参数是$svnRepo1 第triggerTypeList条的tag属性 @目录改动
- 执行jobTest模块的getSubversionDirTest方法，参数是$svnRepo2 第triggerTypeList条的tag属性 @目录改动
- 执行jobTest模块的getSubversionDirTest方法，参数是$svnRepo3 第triggerTypeList条的tag属性 @目录改动
- 执行$result4['dirs'] @0
- 执行$result5['dirs'] @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$table = zenData('repo');
$table->id->range('1-10');
$table->name->range('svn-repo-1,svn-repo-2,svn-repo-3,git-repo-1,gitlab-repo-1,subversion-repo-6');
$table->SCM->range('Subversion{3},Git,Gitlab,Subversion');
$table->prefix->range('``,/trunk,/branches/dev');
$table->product->range('1-10');
$table->acl->range('""');
$table->deleted->range('0');
$table->gen(10);

su('admin');

$jobTest = new jobZenTest();

$svnRepo1 = new stdClass();
$svnRepo1->id = 1;
$svnRepo1->SCM = 'Subversion';
$svnRepo1->prefix = '';
$svnRepo1->client = 'svn';
$svnRepo1->path = '/tmp/svn/repo1';
$svnRepo1->account = '';
$svnRepo1->password = '';
$svnRepo1->encoding = 'utf-8';
r($jobTest->getSubversionDirTest($svnRepo1)) && p('triggerTypeList:tag') && e('目录改动');

$svnRepo2 = new stdClass();
$svnRepo2->id = 2;
$svnRepo2->SCM = 'Subversion';
$svnRepo2->prefix = '/trunk';
$svnRepo2->client = 'svn';
$svnRepo2->path = '/tmp/svn/repo2';
$svnRepo2->account = '';
$svnRepo2->password = '';
$svnRepo2->encoding = 'utf-8';
r($jobTest->getSubversionDirTest($svnRepo2)) && p('triggerTypeList:tag') && e('目录改动');

$svnRepo3 = new stdClass();
$svnRepo3->id = 3;
$svnRepo3->SCM = 'Subversion';
$svnRepo3->prefix = '/branches/dev';
$svnRepo3->client = 'svn';
$svnRepo3->path = '/tmp/svn/repo3';
$svnRepo3->account = '';
$svnRepo3->password = '';
$svnRepo3->encoding = 'utf-8';
r($jobTest->getSubversionDirTest($svnRepo3)) && p('triggerTypeList:tag') && e('目录改动');

$gitRepo = new stdClass();
$gitRepo->id = 4;
$gitRepo->SCM = 'Git';
$gitRepo->prefix = '';
$gitRepo->client = 'git';
$gitRepo->path = '/tmp/git/repo';
$gitRepo->account = '';
$gitRepo->password = '';
$gitRepo->encoding = 'utf-8';
$result4 = $jobTest->getSubversionDirTest($gitRepo);
r(count($result4['dirs'])) && p() && e('0');

$gitlabRepo = new stdClass();
$gitlabRepo->id = 5;
$gitlabRepo->SCM = 'Gitlab';
$gitlabRepo->prefix = '';
$gitlabRepo->client = '';
$gitlabRepo->path = '';
$gitlabRepo->account = '';
$gitlabRepo->password = '';
$gitlabRepo->encoding = 'utf-8';
$result5 = $jobTest->getSubversionDirTest($gitlabRepo);
r(count($result5['dirs'])) && p() && e('0');