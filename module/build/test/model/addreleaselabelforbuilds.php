#!/usr/bin/env php
<?php

/**

title=测试 buildModel::addReleaseLabelForBuilds();
timeout=0
cid=0

- 执行buildTest模块的addReleaseLabelForBuildsTest方法，参数是1, $normalBuilds  @3
- 执行buildTest模块的addReleaseLabelForBuildsTest方法，参数是1, $buildsWithRelease 第0条的value属性 @1
- 执行buildTest模块的addReleaseLabelForBuildsTest方法，参数是2, $buildsProduct2  @2
- 执行buildTest模块的addReleaseLabelForBuildsTest方法，参数是1, array  @0
- 执行buildTest模块的addReleaseLabelForBuildsTest方法，参数是999, $unknownProductBuilds  @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

// 2. 最小化数据准备，避免数据库架构问题
$release = zenData('release');
$release->id->range('1-5');
$release->product->range('1{3},2{2}');
$release->shadow->range('1,2,3,4,5');
$release->build->range('1,2,3{2},4');
$release->name->range('Release_1.0.0,Release_1.1.0,Release_2.0.0,Release_Beta,Release_Final');
$release->status->range('normal{4},terminate');
$release->deleted->range('0{4},1');
$release->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$buildTest = new buildTest();

// 5. 执行测试步骤（至少5个）
// 步骤1：正常产品ID和版本数组，验证返回结构
$normalBuilds = array(1 => '版本1', 2 => '版本2', 3 => '版本3');
r($buildTest->addReleaseLabelForBuildsTest(1, $normalBuilds)) && p() && e('3');

// 步骤2：产品ID为1，有发布关联的版本，验证返回格式
$buildsWithRelease = array(1 => '版本1', 2 => '版本2');
r($buildTest->addReleaseLabelForBuildsTest(1, $buildsWithRelease)) && p('0:value') && e('1');

// 步骤3：产品ID为2，验证不同产品的处理
$buildsProduct2 = array(5 => '版本5', 6 => '版本6');
r($buildTest->addReleaseLabelForBuildsTest(2, $buildsProduct2)) && p() && e('2');

// 步骤4：空版本数组输入，验证空数组处理
r($buildTest->addReleaseLabelForBuildsTest(1, array())) && p() && e('0');

// 步骤5：不存在的产品ID，验证不存在产品的处理
$unknownProductBuilds = array(99 => 'Unknown_Build');
r($buildTest->addReleaseLabelForBuildsTest(999, $unknownProductBuilds)) && p() && e('1');