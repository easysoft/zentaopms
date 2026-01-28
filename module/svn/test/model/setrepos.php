#!/usr/bin/env php
<?php

/**

title=测试 svnModel::setRepos();
timeout=0
cid=18725

- 执行svnTest模块的setReposTest方法，参数是'count'  @4
- 执行svnTest模块的setReposTest方法，参数是'first'
 - 属性name @svn-repo-1
 - 属性SCM @Subversion
- 执行svnTest模块的setReposTest方法，参数是'properties'
 - 属性hasAcl @0
 - 属性hasDesc @0
- 执行svnTest模块的setReposTest方法，参数是'properties'
 - 属性hasSCM @1
 - 属性hasPath @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $tester;
$dao = $tester->dao;

// 清空并准备测试数据
$dao->delete()->from(TABLE_REPO)->exec();

$dao->insert(TABLE_REPO)->data(array(
    'id' => 1,
    'product' => 1,
    'name' => 'svn-repo-1',
    'path' => '/var/svn/repo1',
    'SCM' => 'Subversion',
    'acl' => '{"acl":"open","users":[],"groups":[]}',
    'desc' => 'SVN repository description',
    'synced' => '1',
    'deleted' => '0'
))->exec();

$dao->insert(TABLE_REPO)->data(array(
    'id' => 2,
    'product' => 1,
    'name' => 'svn-repo-2',
    'path' => '/var/svn/repo1',
    'SCM' => 'Subversion',
    'acl' => '{"acl":"open","users":[],"groups":[]}',
    'desc' => 'SVN repository description',
    'synced' => '1',
    'deleted' => '0'
))->exec();

$dao->insert(TABLE_REPO)->data(array(
    'id' => 3,
    'product' => 1,
    'name' => 'svn-repo-3',
    'path' => '/var/svn/repo2',
    'SCM' => 'Subversion',
    'acl' => '{"acl":"open","users":[],"groups":[]}',
    'desc' => 'SVN repository description',
    'synced' => '1',
    'deleted' => '0'
))->exec();

$dao->insert(TABLE_REPO)->data(array(
    'id' => 4,
    'product' => 1,
    'name' => 'svn-repo-4',
    'path' => '/var/svn/repo3',
    'SCM' => 'Subversion',
    'acl' => '{"acl":"open","users":[],"groups":[]}',
    'desc' => 'SVN repository description',
    'synced' => '1',
    'deleted' => '0'
))->exec();

$dao->insert(TABLE_REPO)->data(array(
    'id' => 5,
    'product' => 1,
    'name' => 'svn-repo-5',
    'path' => '/var/svn/repo4',
    'SCM' => 'Subversion',
    'acl' => '{"acl":"open","users":[],"groups":[]}',
    'desc' => 'SVN repository description',
    'synced' => '1',
    'deleted' => '0'
))->exec();

$dao->insert(TABLE_REPO)->data(array(
    'id' => 6,
    'product' => 1,
    'name' => 'git-repo-1',
    'path' => '/var/git/repo1',
    'SCM' => 'Git',
    'acl' => '{"acl":"open","users":[],"groups":[]}',
    'desc' => 'Git repository description',
    'synced' => '1',
    'deleted' => '0'
))->exec();

$svnTest = new svnModelTest();

r($svnTest->setReposTest('count')) && p() && e('4');
r($svnTest->setReposTest('first')) && p('name,SCM') && e('svn-repo-1,Subversion');
r($svnTest->setReposTest('properties')) && p('hasAcl,hasDesc') && e('0,0');
r($svnTest->setReposTest('properties')) && p('hasSCM,hasPath') && e('1,1');

// 测试空仓库情况
$dao->delete()->from(TABLE_REPO)->exec();
r($svnTest->setReposTest('empty')) && p('output') && e('You must set one svn repo.
');