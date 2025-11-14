#!/usr/bin/env php
<?php

/**

title=测试 svnModel::getRepos();
timeout=0
cid=18717

- 执行$result['repos'] @5
- 执行$result['repos'] @https://svn.example.com/repo1
- 执行$result['output'], 'You must set one svn repo.') !== false @1
- 执行$result['repos'] @3
- 执行$directResult @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/svn.unittest.class.php';

global $tester;
$dao = $tester->dao;

$dao->delete()->from(TABLE_REPO)->exec();

for($i = 1; $i <= 10; $i++)
{
    $repo = new stdClass();
    $repo->id = $i;
    $repo->product = 1;
    $repo->name = "svn-repo$i";
    if($i <= 5)
    {
        $repo->path = "https://svn.example.com/repo$i";
        $repo->SCM = 'Subversion';
    }
    else
    {
        $repo->path = "/path/to/other$i";
        $repo->SCM = $i <= 8 ? 'Git' : 'Gitlab';
    }
    $repo->client = '/usr/bin/svn';
    $repo->synced = $i <= 8 ? 1 : 0;
    $repo->deleted = $i <= 8 ? 0 : 1;
    $dao->insert(TABLE_REPO)->data($repo)->exec();
}

su('admin');

$svnTest = new svnTest();

$result = $svnTest->getReposTest();
r(count($result['repos'])) && p() && e('5');

r($result['repos']) && p('0') && e('https://svn.example.com/repo1');

$dao->delete()->from(TABLE_REPO)->exec();
$result = $svnTest->getReposTest();
r(strpos($result['output'], 'You must set one svn repo.') !== false) && p() && e('1');

for($i = 1; $i <= 3; $i++)
{
    $repo = new stdClass();
    $repo->id = $i;
    $repo->product = 1;
    $repo->name = "test-repo$i";
    $repo->path = "https://svn.test.com/repo$i";
    $repo->SCM = 'Subversion';
    $repo->client = '/usr/bin/svn';
    $repo->synced = 1;
    $repo->deleted = 0;
    $dao->insert(TABLE_REPO)->data($repo)->exec();
}

$result = $svnTest->getReposTest();
r(count($result['repos'])) && p() && e('3');

$svnModel = $tester->loadModel('svn');
$directResult = $svnModel->getRepos();
r(count($directResult)) && p() && e('3');