#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildBuildData();
timeout=0
cid=15802

- 步骤1：完整数据测试
 - 属性id @1
 - 属性project @101
 - 属性vname @Build 1.0
 - 属性startdate @2023-01-01
 - 属性releasedate @2023-01-15
 - 属性released @1
- 步骤2：只包含id字段
 - 属性id @2
 - 属性project @0
- 步骤3：部分字段测试
 - 属性id @3
 - 属性project @0
 - 属性vname @Build 2.0
 - 属性releasedate @2023-02-01
- 步骤4：archived为true测试
 - 属性id @4
 - 属性project @0
 - 属性vname @Archived Build
 - 属性archived @1
- 步骤5：startdate为null测试
 - 属性id @5
 - 属性vname @No Start Date
 - 属性startdate @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->buildBuildDataTest(array('id' => 1, 'project' => 101, 'name' => 'Build 1.0', 'startdate' => '2023-01-01', 'releasedate' => '2023-01-15', 'released' => '1', 'archived' => false, 'description' => 'First build'))) && p('id,project,vname,startdate,releasedate,released') && e('1,101,Build 1.0,2023-01-01,2023-01-15,1'); // 步骤1：完整数据测试
r($convertTest->buildBuildDataTest(array('id' => 2))) && p('id,project') && e('2,0'); // 步骤2：只包含id字段
r($convertTest->buildBuildDataTest(array('id' => 3, 'name' => 'Build 2.0', 'releasedate' => '2023-02-01'))) && p('id,project,vname,releasedate') && e('3,0,Build 2.0,2023-02-01'); // 步骤3：部分字段测试
r($convertTest->buildBuildDataTest(array('id' => 4, 'archived' => true, 'name' => 'Archived Build'))) && p('id,project,vname,archived') && e('4,0,Archived Build,1'); // 步骤4：archived为true测试
r($convertTest->buildBuildDataTest(array('id' => 5, 'startdate' => null, 'name' => 'No Start Date'))) && p('id,vname,startdate') && e('5,No Start Date,~~'); // 步骤5：startdate为null测试