#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getProductRepos();
timeout=0
cid=0

- 步骤1：没有代码库时返回空数组 @0
- 步骤2：代码库关联一个产品时该产品下有1个代码库 @1
- 步骤3：代码库关联多个产品时每个产品下都有该代码库 @1
- 步骤4：代码库关联多个产品时第三个产品下也有该代码库 @1
- 步骤5：已删除的代码库不统计 @0
- 步骤6：返回的代码库名称正确 @repo2
- 步骤7：没有关联产品的代码库不产生分组 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$repoTest = new repoModelTest();

/* 步骤1：没有代码库时返回空数组。*/
zenData('ops_repo')->gen(0);
r($repoTest->getProductReposCountTest()) && p() && e('0'); // 步骤1：没有代码库时返回空数组

/* 准备代码库数据：1号关联产品1；2号关联产品2和3；3号已删除；4号未关联产品。*/
$repo = zenData('ops_repo');
$repo->id->range('1-4');
$repo->spaceID->range('1{4}');
$repo->product->range('1,2,3,0');
$repo->name->range('repo1,repo2,repo3,repo4');
$repo->scmType->range('git{4}');
$repo->gitUID->range('uid1,uid2,uid3,uid4');
$repo->acl->range('open{4}');
$repo->status->range('active{4}');
$repo->deleted->range('0,0,1,0');
$repo->gen(4);
$tester->dao->update(TABLE_REPO)->set('product')->eq('2,3')->where('id')->eq(2)->exec();
$tester->dao->update(TABLE_REPO)->set('product')->eq('')->where('id')->eq(4)->exec();

r($repoTest->getProductReposProductCountTest(1))               && p() && e('1');      // 步骤2：代码库关联一个产品时该产品下有1个代码库
r($repoTest->getProductReposProductCountTest(2))               && p() && e('1');      // 步骤3：代码库关联多个产品时每个产品下都有该代码库
r($repoTest->getProductReposProductCountTest(3))               && p() && e('1');      // 步骤4：代码库关联多个产品时第三个产品下也有该代码库
r($repoTest->getProductReposProductCountTest(4))               && p() && e('0');      // 步骤5：已删除的代码库不统计
r($repoTest->getProductReposProductRepoNameTest(2, 2))         && p() && e('repo2');  // 步骤6：返回的代码库名称正确
r($repoTest->getProductReposCountTest())                       && p() && e('3');      // 步骤7：没有关联产品的代码库不产生分组
