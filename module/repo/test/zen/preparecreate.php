#!/usr/bin/env php
<?php

/**

title=测试 repoZen::prepareCreate();
timeout=0
cid=0

- 步骤1：正常Git仓库创建
 - 属性SCM @Git
 - 属性name @testgit
 - 属性path @/tmp/git
- 步骤2：Gitlab仓库创建
 - 属性SCM @Gitlab
 - 属性extra @123
- 步骤3：Subversion仓库创建
 - 属性SCM @Subversion
 - 属性name @testsvn
- 步骤4：无效输入测试 @0
- 步骤5：管道服务器模式
 - 属性SCM @Gitlab
 - 属性password @token123

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('repo');
$table->id->range('1-10');
$table->SCM->range('Git,Gitlab,Subversion');
$table->name->range('testrepo{5}');
$table->path->range('/tmp/test{5}');
$table->encoding->range('UTF-8{10}');
$table->client->range('git,svn');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$repoTest = new repoTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($repoTest->prepareCreateTest(array('SCM' => 'Git', 'name' => 'testgit', 'path' => '/tmp/git', 'client' => 'git', 'account' => '', 'password' => '', 'encoding' => 'UTF-8', 'product' => array('1','2'), 'projects' => array('1')), false)) && p('SCM,name,path') && e('Git,testgit,/tmp/git'); // 步骤1：正常Git仓库创建
r($repoTest->prepareCreateTest(array('SCM' => 'Gitlab', 'name' => 'testgitlab', 'serviceProject' => '123', 'encoding' => 'UTF-8', 'path' => '', 'client' => '', 'product' => array(), 'projects' => array()), false)) && p('SCM,extra') && e('Gitlab,123'); // 步骤2：Gitlab仓库创建
r($repoTest->prepareCreateTest(array('SCM' => 'Subversion', 'name' => 'testsvn', 'path' => '/tmp/svn', 'client' => 'svn', 'account' => 'admin', 'password' => 'pass', 'encoding' => 'UTF-8', 'product' => array(), 'projects' => array()), false)) && p('SCM,name') && e('Subversion,testsvn'); // 步骤3：Subversion仓库创建
r($repoTest->prepareCreateTest(array('SCM' => 'Git', 'name' => 'testinvalid', 'path' => '', 'client' => '', 'encoding' => 'UTF-8', 'product' => array(), 'projects' => array()), false)) && p() && e('0'); // 步骤4：无效输入测试
r($repoTest->prepareCreateTest(array('SCM' => 'Gitlab', 'name' => 'pipeline', 'serviceProject' => '456', 'serviceToken' => 'token123', 'encoding' => 'UTF-8', 'product' => array(), 'projects' => array()), true)) && p('SCM,password') && e('Gitlab,token123'); // 步骤5：管道服务器模式