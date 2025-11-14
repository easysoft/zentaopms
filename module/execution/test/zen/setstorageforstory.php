#!/usr/bin/env php
<?php

/**

title=测试 executionZen::setStorageForStory();
timeout=0
cid=16443

- 步骤1：按模块浏览设置存储 @1
- 步骤2：按产品浏览设置存储 @3
- 步骤3：按分支浏览设置存储 @0
- 步骤4：切换执行ID后按模块设置存储 @2
- 步骤5：无效模块ID处理 @0
- 步骤6：按产品浏览设置不同产品ID @10
- 步骤7：空字符串参数处理 @0
- 步骤8：无效类型参数处理 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('module');
$table->id->range('1-10');
$table->root->range('1{3},2{3},3{4}');
$table->type->range('story');
$table->name->range('模块1,模块2,模块3,模块4,模块5,模块6,模块7,模块8,模块9,模块10');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionZenTest = new executionZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($executionZenTest->setStorageForStoryTest('1', 'bymodule', '1', 'id_desc')) && p() && e('1');    // 步骤1：按模块浏览设置存储
r($executionZenTest->setStorageForStoryTest('2', 'byproduct', '3', 'name_asc')) && p() && e('3');  // 步骤2：按产品浏览设置存储
r($executionZenTest->setStorageForStoryTest('3', 'bybranch', '2', 'pri_desc')) && p() && e('0');   // 步骤3：按分支浏览设置存储
r($executionZenTest->setStorageForStoryTest('2', 'bymodule', '5', 'id_asc')) && p() && e('2');     // 步骤4：切换执行ID后按模块设置存储
r($executionZenTest->setStorageForStoryTest('1', 'bymodule', '999', 'id_desc')) && p() && e('0');  // 步骤5：无效模块ID处理
r($executionZenTest->setStorageForStoryTest('4', 'byproduct', '10', 'name_desc')) && p() && e('10'); // 步骤6：按产品浏览设置不同产品ID
r($executionZenTest->setStorageForStoryTest('5', 'byproduct', '', 'id_asc')) && p() && e('0');     // 步骤7：空字符串参数处理
r($executionZenTest->setStorageForStoryTest('6', 'unknown', '1', 'id_desc')) && p() && e('0');     // 步骤8：无效类型参数处理