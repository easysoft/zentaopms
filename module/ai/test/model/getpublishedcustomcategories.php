#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getPublishedCustomCategories();
timeout=0
cid=15047

- 步骤1：测试空数据情况 @0
- 步骤2：测试已发布小程序的分类数量 @4
- 步骤3：验证第一个分类 @custom_work
- 步骤4：测试分类去重功能 @2
- 步骤5：测试未发布小程序不返回分类 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest = new aiModelTest();

$table = zenData('ai_miniprogram');
$table->gen(0); // 清空数据

r(count($aiTest->getPublishedCustomCategoriesTest())) && p() && e('0'); // 步骤1：测试空数据情况

$table = zenData('ai_miniprogram');
$table->id->range('1-8');
$table->name->range('test1,test2,test3,test4,test5,test6,test7,test8');
$table->category->range('custom_work{2},custom_personal{2},work{2},personal{2}');
$table->desc->range('This is test miniprogram{8}');
$table->model->range('1{8}');
$table->icon->range('writinghand-7{8}');
$table->createdBy->range('admin{8}');
$table->createdDate->range('`2024-01-01 00:00:00`,`2024-01-02 00:00:00`');
$table->editedBy->range('admin{8}');
$table->editedDate->range('`2024-01-01 00:00:00`,`2024-01-02 00:00:00`');
$table->published->range('1{8}');
$table->publishedDate->range('`2024-01-01 00:00:00`,`2024-01-02 00:00:00`');
$table->deleted->range('0{8}');
$table->prompt->range('This is test prompt{8}');
$table->builtIn->range('0{8}');
$table->gen(8);

r(count($aiTest->getPublishedCustomCategoriesTest())) && p() && e('4'); // 步骤2：测试已发布小程序的分类数量
r($aiTest->getPublishedCustomCategoriesTest()) && p('0') && e('custom_work'); // 步骤3：验证第一个分类

$table = zenData('ai_miniprogram');
$table->gen(0); // 清空数据
$table->id->range('1-5');
$table->name->range('test1,test2,test3,test4,test5');
$table->category->range('custom_test1{2},custom_test2{2},custom_test1');
$table->desc->range('This is test miniprogram{5}');
$table->model->range('1{5}');
$table->icon->range('writinghand-7{5}');
$table->createdBy->range('admin{5}');
$table->createdDate->range('`2024-01-01 00:00:00`');
$table->editedBy->range('admin{5}');
$table->editedDate->range('`2024-01-01 00:00:00`');
$table->published->range('1{5}');
$table->publishedDate->range('`2024-01-01 00:00:00`');
$table->deleted->range('0{5}');
$table->prompt->range('This is test prompt{5}');
$table->builtIn->range('0{5}');
$table->gen(5);

r(count($aiTest->getPublishedCustomCategoriesTest())) && p() && e('2'); // 步骤4：测试分类去重功能

$table = zenData('ai_miniprogram');
$table->gen(0); // 清空数据
$table->id->range('1-3');
$table->name->range('test1,test2,test3');
$table->category->range('unpublished1,unpublished2,unpublished3');
$table->desc->range('This is test miniprogram{3}');
$table->model->range('1{3}');
$table->icon->range('writinghand-7{3}');
$table->createdBy->range('admin{3}');
$table->createdDate->range('`2024-01-01 00:00:00`');
$table->editedBy->range('admin{3}');
$table->editedDate->range('`2024-01-01 00:00:00`');
$table->published->range('0{3}');
$table->deleted->range('0{3}');
$table->prompt->range('This is test prompt{3}');
$table->builtIn->range('0{3}');
$table->gen(3);

ob_start();
$result = count($aiTest->getPublishedCustomCategoriesTest());
ob_end_clean();
r($result) && p() && e('0'); // 步骤5：测试未发布小程序不返回分类