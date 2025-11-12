#!/usr/bin/env php
<?php

/**

title=测试 repoZen::prepareEdit();
timeout=0
cid=0

- 执行result1 = $repoZenTest模块的prepareEditTest方法，参数是$normalFormData, $oldGitRepo, 'normal' 属性acl @{"acl":"open"}
- 执行result2 = $repoZenTest模块的prepareEditTest方法，参数是$gitlabFormData, $oldGitRepo, 'normal' 属性extra @456
- 执行result3 = $repoZenTest模块的prepareEditTest方法，参数是$pathChangedFormData, $oldGitRepo, 'normal' 属性synced @0
- 执行result4 = $repoZenTest模块的prepareEditTest方法，参数是$svnFormData, $oldSvnRepo, 'normal' 属性prefix @/trunk
- 执行result5 = $repoZenTest模块的prepareEditTest方法，参数是$gitSwitchFormData, $oldSvnRepo, 'normal' 属性prefix @~~
- 执行result6 = $repoZenTest模块的prepareEditTest方法，参数是$clientWithSpaceFormData, $oldGitRepo, 'normal' 属性client @"/usr/local/bin/git client"
- 执行result7 = $repoZenTest模块的prepareEditTest方法，参数是$normalFormData, $oldGitRepo, 'acl_error'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

su('admin');

$repoZenTest = new repoZenTest();

// 准备旧仓库数据
$oldGitRepo = new stdclass();
$oldGitRepo->id = 1;
$oldGitRepo->SCM = 'Git';
$oldGitRepo->client = 'git';
$oldGitRepo->path = '/old/path';
$oldGitRepo->product = '1';
$oldGitRepo->projects = '1';

$oldSvnRepo = new stdclass();
$oldSvnRepo->id = 2;
$oldSvnRepo->SCM = 'Subversion';
$oldSvnRepo->client = 'svn';
$oldSvnRepo->path = '/svn/repo';
$oldSvnRepo->product = '1';
$oldSvnRepo->projects = '1';

// 准备表单数据
$normalFormData = array(
    'SCM' => 'Git',
    'client' => 'git',
    'path' => '/new/path',
    'product' => '1,2',
    'projects' => '1,2',
    'acl' => array('acl' => 'open')
);

$gitlabFormData = array(
    'SCM' => 'Gitlab',
    'client' => '',
    'path' => '/gitlab/path',
    'serviceProject' => '456',
    'acl' => array('acl' => 'open')
);

$pathChangedFormData = array(
    'SCM' => 'Git',
    'client' => 'git',
    'path' => '/changed/path',
    'product' => '1',
    'projects' => '1',
    'acl' => array('acl' => 'open')
);

$svnFormData = array(
    'SCM' => 'Subversion',
    'client' => 'svn',
    'path' => '/svn/newpath',
    'product' => '1',
    'projects' => '1',
    'acl' => array('acl' => 'open')
);

$gitSwitchFormData = array(
    'SCM' => 'Git',
    'client' => 'git',
    'path' => '/git/path',
    'product' => '1',
    'projects' => '1',
    'acl' => array('acl' => 'open')
);

$clientWithSpaceFormData = array(
    'SCM' => 'Git',
    'client' => '/usr/local/bin/git client',
    'path' => '/repo/path',
    'product' => '1',
    'projects' => '1',
    'acl' => array('acl' => 'open')
);

// 测试步骤1: 正常编辑Git仓库
r($result1 = $repoZenTest->prepareEditTest($normalFormData, $oldGitRepo, 'normal')) && p('acl') && e('{"acl":"open"}');

// 测试步骤2: 编辑Gitlab仓库特殊字段
r($result2 = $repoZenTest->prepareEditTest($gitlabFormData, $oldGitRepo, 'normal')) && p('extra') && e('456');

// 测试步骤3: 编辑时修改path导致synced重置
r($result3 = $repoZenTest->prepareEditTest($pathChangedFormData, $oldGitRepo, 'normal')) && p('synced') && e('0');

// 测试步骤4: SVN仓库编辑prefix处理
r($result4 = $repoZenTest->prepareEditTest($svnFormData, $oldSvnRepo, 'normal')) && p('prefix') && e('/trunk');

// 测试步骤5: 从SVN切换到Git时prefix清空
r($result5 = $repoZenTest->prepareEditTest($gitSwitchFormData, $oldSvnRepo, 'normal')) && p('prefix') && e('~~');

// 测试步骤6: 客户端包含空格时的引号处理
r($result6 = $repoZenTest->prepareEditTest($clientWithSpaceFormData, $oldGitRepo, 'normal')) && p('client') && e('"/usr/local/bin/git client"');

// 测试步骤7: ACL配置错误场景
r($result7 = $repoZenTest->prepareEditTest($normalFormData, $oldGitRepo, 'acl_error')) && p() && e('0');