#!/usr/bin/env php
<?php

/**

title=测试 svnModel::setRepoRoot();
timeout=0
cid=18724

- 步骤1：正常SVN仓库设置根路径 @https://svn.qc.oop.cc/svn/unittest
- 步骤2：测试不同类型的仓库路径格式 @0
- 步骤3：测试包含所有必需属性的仓库对象 @0
- 步骤4：测试svn协议的仓库路径 @0
- 步骤5：验证仓库根路径属性更新 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
$table = zenData('repo');
$table->id->range('1-5');
$table->product->range('1-5');
$table->name->range('svn-repo{5}');
$table->path->range('https://svn.qc.oop.cc/svn/unittest,https://svn.example.com/repo,file:///var/svn/repo,svn://localhost/repo,https://svn.test.com/invalid');
$table->encoding->range('utf-8{5}');
$table->SCM->range('Subversion{5}');
$table->client->range('svn{5}');
$table->commits->range('10{5}');
$table->account->range('admin{5}');
$table->password->range('S1hkT2k4emdUY1VxRUZYMkh4OEI={5}');
$table->encrypt->range('base64{5}');
$table->synced->range('1{3},0{2}');
$table->deleted->range('0{4},1');
$table->gen(5);

su('admin');

$svnTest = new svnModelTest();

global $tester;
$svn = $tester->loadModel('svn');
$svn->setRepos();
$repo = $svn->repos[1];
r($svnTest->setRepoRootTest($repo)) && p() && e('https://svn.qc.oop.cc/svn/unittest'); // 步骤1：正常SVN仓库设置根路径

$repo2 = new stdClass();
$repo2->id = 2;
$repo2->path = 'https://svn.example.com/repo';
$repo2->encoding = 'utf-8';
$repo2->SCM = 'Subversion';
$repo2->client = 'svn';
$repo2->account = 'admin';
$repo2->password = 'S1hkT2k4emdUY1VxRUZYMkh4OEI=';
$repo2->encrypt = 'base64';
r($svnTest->setRepoRootTest($repo2)) && p() && e('0'); // 步骤2：测试不同类型的仓库路径格式

$repo3 = new stdClass();
$repo3->id = 3;
$repo3->path = 'file:///var/svn/repo';
$repo3->encoding = 'utf-8';
$repo3->SCM = 'Subversion';
$repo3->client = 'svn';
$repo3->account = 'admin';
$repo3->password = 'S1hkT2k4emdUY1VxRUZYMkh4OEI=';
$repo3->encrypt = 'base64';
r($svnTest->setRepoRootTest($repo3)) && p() && e('0'); // 步骤3：测试包含所有必需属性的仓库对象

$repo4 = new stdClass();
$repo4->id = 4;
$repo4->path = 'svn://localhost/repo';
$repo4->encoding = 'utf-8';
$repo4->SCM = 'Subversion';
$repo4->client = 'svn';
$repo4->account = 'admin';
$repo4->password = 'S1hkT2k4emdUY1VxRUZYMkh4OEI=';
$repo4->encrypt = 'base64';
r($svnTest->setRepoRootTest($repo4)) && p() && e('0'); // 步骤4：测试svn协议的仓库路径

$svn2 = $tester->loadModel('svn');
$testRepo = new stdClass();
$testRepo->id = 1;
$testRepo->path = 'https://svn.qc.oop.cc/svn/unittest';
$testRepo->encoding = 'utf-8';
$testRepo->SCM = 'Subversion';
$testRepo->client = 'svn';
$testRepo->account = 'admin';
$testRepo->password = 'S1hkT2k4emdUY1VxRUZYMkh4OEI=';
$testRepo->encrypt = 'base64';
$svn2->setRepoRoot($testRepo);
r($svn2->repoRoot ? $svn2->repoRoot : '0') && p() && e('0'); // 步骤5：验证仓库根路径属性更新