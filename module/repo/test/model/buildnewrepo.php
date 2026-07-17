#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->buildNewRepo();
timeout=0
cid=18123

- 测试 buildNewRepo Gitlab 类型 scmType @git
- 测试 buildNewRepo Gitlab 类型 connector slug @group/testrepo
- 测试 buildNewRepo Gitea 类型 connector slug @proj_a
- 测试 buildNewRepo Subversion 类型 connector slug @svn/repo
- 测试 buildNewRepo 未知 SCM 类型 scmType 为空 @~~
*/

$repo = new repoModelTest();

$oldRepoData = array(
    'id'             => 123,
    'SCM'            => 'Gitlab',
    'name'           => 'unit_test_repo',
    'desc'           => 'unit test description',
    'serviceHost'    => 1,
    'serviceProject' => '1120',
    'product'        => '1,2',
    'deleted'        => 0,
    'path'           => 'https://gitlab.example.com/group/testrepo',
);
$result = $repo->buildNewRepoTest($oldRepoData, 'open', 'system');
r($result) && p('scmType') && e('git');
$connector = json_decode($result->connector, true);
r($connector) && p('slug') && e('group/testrepo');

$giteaRepoData = array(
    'id'             => 124,
    'SCM'            => 'Gitea',
    'name'           => 'unit_test_gitea_repo',
    'desc'           => 'unit test description',
    'serviceHost'    => 1,
    'serviceProject' => 'proj_a',
    'product'        => '1',
    'deleted'        => 0,
);
$result = $repo->buildNewRepoTest($giteaRepoData, 'private', 'admin1');
$connector = json_decode($result->connector, true);
r($connector) && p('slug') && e('proj_a');

$svnRepoData = array(
    'id'          => 125,
    'SCM'         => 'Subversion',
    'name'        => 'unit_test_svn_repo',
    'desc'        => 'unit test description',
    'serviceHost' => 1,
    'product'     => '1',
    'deleted'     => 0,
    'path'        => 'https://svn.example.com/svn/repo',
    'account'     => 'svnUser',
    'password'    => 'svnPass',
);
$result = $repo->buildNewRepoTest($svnRepoData, 'open', 'system');
$connector = json_decode($result->connector, true);
r($connector) && p('slug') && e('svn/repo');

$unknownRepoData = array(
    'id'          => 126,
    'SCM'         => 'GitHub',
    'name'        => 'unit_test_unknown_repo',
    'desc'        => 'unit test description',
    'serviceHost' => 1,
    'product'     => '1',
    'deleted'     => 0,
);
$result = $repo->buildNewRepoTest($unknownRepoData, 'open', 'system');
r($result) && p('scmType') && e('~~');
