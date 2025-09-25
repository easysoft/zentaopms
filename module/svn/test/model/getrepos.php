#!/usr/bin/env php
<?php

/**

title=测试 svnModel::getRepos();
timeout=0
cid=0

- 步骤1：正常获取Subversion仓库数量 @5
- 步骤2：验证返回第一个仓库路径 @1
- 步骤3：空仓库情况输出提示信息 @You must set one svn repo.
- 步骤4：验证返回数据数量 @3
- 步骤5：验证getRepos方法调用正确性 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/svn.unittest.class.php';

// 使用默认测试数据，但改为Subversion类型
zenData('repo')->gen(0);

$table = zenData('repo');
$table->id->range('1-10');
$table->name->range('svn-test{10}');
$table->path->range('1-10', 'https://svn.qc.oop.cc/svn/unittest%s');
$table->SCM->range('Subversion{5},Git{3},Gitlab{2}');
$table->synced->range('1{5},0{5}');
$table->deleted->range('0{8},1{2}');
$table->gen(10);

su('admin');

$svnTest = new svnTest();

$result = $svnTest->getReposTest();
r(count($result['repos'])) && p() && e('5'); // 步骤1：正常获取Subversion仓库数量

r($result['repos']) && p('0') && e('1'); // 步骤2：验证返回第一个仓库路径

// 测试空仓库情况
zenData('repo')->gen(0);

$result = $svnTest->getReposTest();
r($result['output']) && p() && e('You must set one svn repo.'); // 步骤3：空仓库情况输出提示信息

// 恢复数据，测试返回类型
$table = zenData('repo');
$table->id->range('1-3');
$table->name->range('test-repo{3}');
$table->path->range('1-3', 'https://svn.example.com/test%s');
$table->SCM->range('Subversion{3}');
$table->synced->range('1{3}');
$table->deleted->range('0{3}');
$table->gen(3);

$result = $svnTest->getReposTest();
r(count($result['repos'])) && p() && e('3'); // 步骤4：验证返回数据数量

// 验证方法调用正确性
global $tester;
$svnModel = $tester->loadModel('svn');
$directResult = $svnModel->getRepos();
r(count($directResult)) && p() && e('3'); // 步骤5：验证getRepos方法调用正确性