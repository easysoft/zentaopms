#!/usr/bin/env php
<?php

/**

title=测试 repoZen::prepareEdit();
timeout=0
cid=0

- 执行repoTest模块的prepareEditTest方法，参数是$formData1, $oldRepo1, false
 - 属性path @/home/git/new-path
 - 属性synced @0
- 执行repoTest模块的prepareEditTest方法，参数是$formData2, $oldRepo2, false 属性extra @project123
- 执行repoTest模块的prepareEditTest方法，参数是$formData3, $oldRepo3, false
 - 属性SCM @Subversion
 - 属性prefix @/test/prefix
- 执行repoTest模块的prepareEditTest方法，参数是$formData4, $oldRepo4, true 属性password @token123
- 执行repoTest模块的prepareEditTest方法，参数是$formData5, $oldRepo5, false  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

zenData('repo')->loadYaml('repo_prepareedit', false, 2)->gen(10);

su('admin');

$repoTest = new repoTest();

// 测试步骤1：正常编辑Git版本库
$formData1 = array(
    'name' => 'test-repo-edited',
    'path' => '/home/git/new-path',
    'SCM' => 'Git',
    'client' => 'git',
    'account' => 'admin',
    'password' => 'newpwd',
    'product' => array('1', '2'),
    'projects' => array('1'),
    'desc' => 'edited description'
);
$oldRepo1 = (object)array(
    'id' => 1,
    'client' => 'git',
    'path' => '/home/git/old-path',
    'SCM' => 'Git'
);
r($repoTest->prepareEditTest($formData1, $oldRepo1, false)) && p('path,synced') && e('/home/git/new-path,0');

// 测试步骤2：编辑Gitlab版本库设置额外属性
$formData2 = array(
    'name' => 'gitlab-repo',
    'path' => '/gitlab/path',
    'SCM' => 'Gitlab',
    'client' => 'git',
    'serviceProject' => 'project123',
    'product' => array('1'),
    'projects' => array('2')
);
$oldRepo2 = (object)array(
    'id' => 2,
    'client' => 'git',
    'path' => '/gitlab/path',
    'SCM' => 'Gitlab'
);
r($repoTest->prepareEditTest($formData2, $oldRepo2, false)) && p('extra') && e('project123');

// 测试步骤3：编辑Subversion版本库生成prefix
$formData3 = array(
    'name' => 'svn-repo',
    'path' => '/svn/repo',
    'SCM' => 'Subversion',
    'client' => 'svn',
    'account' => 'svnuser',
    'password' => 'svnpwd',
    'product' => array(),
    'projects' => array()
);
$oldRepo3 = (object)array(
    'id' => 3,
    'client' => 'svn',
    'path' => '/svn/repo',
    'SCM' => 'Subversion'
);
r($repoTest->prepareEditTest($formData3, $oldRepo3, false)) && p('SCM,prefix') && e('Subversion,/test/prefix');

// 测试步骤4：测试流水线服务器模式
$formData4 = array(
    'name' => 'pipeline-repo',
    'path' => '/pipeline/repo',
    'SCM' => 'Git',
    'client' => 'git',
    'serviceToken' => 'token123',
    'product' => array('3'),
    'projects' => array('3')
);
$oldRepo4 = (object)array(
    'id' => 4,
    'client' => 'git',
    'path' => '/pipeline/repo',
    'SCM' => 'Git'
);
r($repoTest->prepareEditTest($formData4, $oldRepo4, true)) && p('password') && e('token123');

// 测试步骤5：测试ACL权限验证失败
$formData5 = array(
    'name' => 'acl-repo',
    'path' => '/acl/repo',
    'SCM' => 'Git',
    'client' => 'git',
    'acl' => 'custom',
    'groups' => array(),
    'users' => array(),
    'product' => array(),
    'projects' => array()
);
$oldRepo5 = (object)array(
    'id' => 5,
    'client' => 'git',
    'path' => '/acl/repo',
    'SCM' => 'Git'
);
r($repoTest->prepareEditTest($formData5, $oldRepo5, false)) && p() && e('0');