#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getUsedCustomCategories();
timeout=0
cid=15052

- 步骤1：有数据情况，期望返回3个分类 @3
- 步骤2：验证第一个分类 @work
- 步骤3：验证第二个分类属性1 @personal
- 步骤4：验证第三个分类属性2 @creative
- 步骤5：验证life分类被删除不存在 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('ai_miniprogram');
$table->id->range('1-10');
$table->name->range('test1,test2,test3,test4,test5,test6,test7,test8,test9,test10');
$table->category->range('work{3},personal{3},creative{3},life{1}');
$table->desc->range('This is test miniprogram{10}');
$table->model->range('1{10}');
$table->icon->range('writinghand-7{10}');
$table->createdBy->range('admin{10}');
$table->createdDate->range('`2024-01-01 00:00:00`,`2024-01-02 00:00:00`');
$table->editedBy->range('admin{10}');
$table->editedDate->range('`2024-01-01 00:00:00`,`2024-01-02 00:00:00`');
$table->published->range('0{3},1{7}');
$table->publishedDate->range('`2024-01-01 00:00:00`,`2024-01-02 00:00:00`');
$table->deleted->range('0{8},1{2}');
$table->prompt->range('This is test prompt{10}');
$table->builtIn->range('0{10}');
$table->gen(10);

su('admin');

$aiTest = new aiModelTest();

r(count($aiTest->getUsedCustomCategoriesTest())) && p() && e('3'); // 步骤1：有数据情况，期望返回3个分类
r($aiTest->getUsedCustomCategoriesTest()) && p('0') && e('work'); // 步骤2：验证第一个分类
r($aiTest->getUsedCustomCategoriesTest()) && p('1') && e('personal'); // 步骤3：验证第二个分类
r($aiTest->getUsedCustomCategoriesTest()) && p('2') && e('creative'); // 步骤4：验证第三个分类
r(in_array('life', $aiTest->getUsedCustomCategoriesTest())) && p() && e('0'); // 步骤5：验证life分类被删除不存在