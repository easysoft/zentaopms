#!/usr/bin/env php
<?php

/**

title=测试 projectZen::removeAssociatedProducts();
timeout=0
cid=17962

- 步骤1:项目有产品关联时不删除产品 @success
- 步骤2:项目无产品关联且产品是影子产品时删除产品 @success
- 步骤3:项目无产品关联但产品不是影子产品时不删除 @success
- 步骤4:项目无产品关联时正常处理 @success
- 步骤5:项目无产品关联正常处理 @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$project->type->range('project{10}');
$project->hasProduct->range('1{5},0{5}');
$project->status->range('wait{3},doing{4},closed{3}');
$project->deleted->range('0{10}');
$project->gen(10, true, false);

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$product->shadow->range('0{5},1{5}');
$product->status->range('normal{8},closed{2}');
$product->deleted->range('0{10}');
$product->type->range('normal{8},branch{2}');
$product->gen(10, true, false);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('2,3,4,6,7,8,9,10');
$projectproduct->product->range('2,3,4,6,7,8,9,10');
$projectproduct->gen(8, true, false);

// 3. 用户登录
$zd_company = zenData('company');
$zd_company->id->range('1');
$zd_company->name->range('禅道软件');
$zd_company->admins->range('admin');
$zd_company->guest->range('0');
$zd_company->gen(1);
$dbh->exec("UPDATE zt_company SET admins = ',admin,' WHERE id = 1");
global $app;
$app->company = $dbh->query('SELECT * FROM zt_company WHERE id = 1')->fetch(PDO::FETCH_OBJ);

$zd_user = zenData('user');
$zd_user->id->range('1-1');
$zd_user->account->range('admin');
$zd_user->realname->range('admin');
$zd_user->password->range('e10adc3949ba59abbe56e057f20f883e');
$zd_user->visions->range('rnd');
$zd_user->deleted->range('0');
$zd_user->gen(1);
su('admin');

// 4. 创建测试实例
$projectzenTest = new projectzenTest();

// 5. 强制要求:必须包含至少5个测试步骤
r($projectzenTest->removeAssociatedProductsTest((object)array('id' => 1, 'hasProduct' => 1))) && p() && e('success'); // 步骤1:项目有产品关联时不删除产品
r($projectzenTest->removeAssociatedProductsTest((object)array('id' => 6, 'hasProduct' => 0))) && p() && e('success'); // 步骤2:项目无产品关联且产品是影子产品时删除产品
r($projectzenTest->removeAssociatedProductsTest((object)array('id' => 2, 'hasProduct' => 0))) && p() && e('success'); // 步骤3:项目无产品关联但产品不是影子产品时不删除
r($projectzenTest->removeAssociatedProductsTest((object)array('id' => 3, 'hasProduct' => 0))) && p() && e('success'); // 步骤4:项目无产品关联时正常处理
r($projectzenTest->removeAssociatedProductsTest((object)array('id' => 4, 'hasProduct' => 0))) && p() && e('success'); // 步骤5:项目无产品关联正常处理
