#!/usr/bin/env php
<?php

/**

title=测试 svnModel::setRepos();
timeout=0
cid=0

- 执行svnTest模块的setReposTest方法，参数是'count'  @4
- 执行svnTest模块的setReposTest方法，参数是'first'
 - 属性name @svn-repo-1
 - 属性SCM @Subversion
- 执行svnTest模块的setReposTest方法，参数是'properties'
 - 属性hasAcl @0
 - 属性hasDesc @0
- 执行svnTest模块的setReposTest方法，参数是'empty' 属性output @You must set one svn repo.
- 执行svnTest模块的setReposTest方法，参数是'count'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/svn.unittest.class.php';

$table = zenData('repo');
$table->id->range('1-10');
$table->product->range('1{3},2{3},3{3},4{1}');
$table->name->range('svn-repo-1,svn-repo-2,svn-repo-3,git-repo-1,git-repo-2,gitlab-repo-1,gitlab-repo-2,svn-repo-4,svn-repo-5,test-repo-1');
$table->path->range('/var/svn/repo1,/var/svn/repo1,/var/svn/repo2,/var/git/repo1,/var/git/repo2,/var/gitlab/repo1,/var/gitlab/repo2,/var/svn/repo3,/var/svn/repo4,/var/test/repo1');
$table->SCM->range('Subversion{5},Git{2},Gitlab{2},Unknown{1}');
$table->acl->range('{"acl":"open","users":[],"groups":[]}');
$table->desc->range('SVN repository description');
$table->synced->range('1');
$table->deleted->range('0');
$table->gen(10);

su('admin');

$svnTest = new svnTest();

r($svnTest->setReposTest('count')) && p() && e('4');
r($svnTest->setReposTest('first')) && p('name,SCM') && e('svn-repo-1,Subversion');
r($svnTest->setReposTest('properties')) && p('hasAcl,hasDesc') && e('0,0');

zenData('repo')->gen(0);
r($svnTest->setReposTest('empty')) && p('output') && e('You must set one svn repo.');

zenData('repo')->gen(0);
$table = zenData('repo');
$table->id->range('1');
$table->name->range('single-svn');
$table->path->range('/var/svn/single');
$table->SCM->range('Subversion');
$table->synced->range('1');
$table->deleted->range('0');
$table->gen(1);
r($svnTest->setReposTest('count')) && p() && e('1');