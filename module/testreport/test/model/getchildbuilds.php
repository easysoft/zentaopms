#!/usr/bin/env php
<?php

/**

title=测试 testreportModel::getChildBuilds();
timeout=0
cid=19117

- 步骤1：获取有子版本的版本构建列表 @8:子版本1,9:子版本2

- 步骤2：获取没有子版本的版本构建列表 @0
- 步骤3：获取空版本构建列表 @0
- 步骤4：获取混合版本构建列表（有些有子版本，有些没有） @8:子版本1
- 步骤5：获取包含无效版本ID的版本构建列表 @8:子版本1,9:子版本2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreport.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('build');
$table->id->range('1-10');
$table->project->range('1{2},2{2},3{6}');
$table->product->range('1{10}');
$table->name->range('版本1.0,版本1.1,版本1.2,版本2.0,版本2.1,版本2.2,版本3.0,子版本1,子版本2,子版本3');
$table->builds->range('[]{4},8,9,8{2},10,[]{2}');
$table->bugs->range('1,2,3{2},[]{2},4,5{3}');
$table->stories->range('1{3},2{2},[]{2},3{3}');
$table->deleted->range('0{9},1');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testreportTest = new testreportTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($testreportTest->getChildBuildsTest(array(5, 6))) && p() && e('8:子版本1,9:子版本2'); // 步骤1：获取有子版本的版本构建列表
r($testreportTest->getChildBuildsTest(array(1, 2, 3))) && p() && e('0'); // 步骤2：获取没有子版本的版本构建列表
r($testreportTest->getChildBuildsTest(array())) && p() && e('0'); // 步骤3：获取空版本构建列表
r($testreportTest->getChildBuildsTest(array(4, 5, 7, 8))) && p() && e('8:子版本1'); // 步骤4：获取混合版本构建列表（有些有子版本，有些没有）
r($testreportTest->getChildBuildsTest(array(5, 6, 999))) && p() && e('8:子版本1,9:子版本2'); // 步骤5：获取包含无效版本ID的版本构建列表