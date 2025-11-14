#!/usr/bin/env php
<?php

/**

title=测试 groupModel::getProductsForAdminGroup();
timeout=0
cid=16713

- 步骤1：无参数时返回有效产品数量 @8
- 步骤2：空数组参数时返回有效产品数量 @8
- 步骤3：验证第一个产品名称属性1 @正常产品1
- 步骤4：普通模式产品名称属性1 @正常产品1
- 步骤5：ALM模式产品名称格式属性1 @项目集1/正常产品1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/group.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-10');
$table->program->range('1{3},2{3},0{4}');
$table->name->range('正常产品1,正常产品2,正常产品3,正常产品4,正常产品5,正常产品6,正常产品7,正常产品8,删除产品9,影子产品10');
$table->code->range('product1,product2,product3,product4,product5,product6,product7,product8,product9,product10');
$table->shadow->range('0{8},0{1},1{1}');
$table->deleted->range('0{8},1{1},0{1}');
$table->vision->range('rnd{10}');
$table->status->range('normal{8},closed{2}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$groupTest = new groupTest();

// 保存原始系统模式配置
global $config;
$originalSystemMode = $config->systemMode;

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($groupTest->getProductsForAdminGroupCountTest())        && p()    && e('8');         // 步骤1：无参数时返回有效产品数量
r($groupTest->getProductsForAdminGroupCountTest(array())) && p()    && e('8');         // 步骤2：空数组参数时返回有效产品数量
r($groupTest->getProductsForAdminGroupTest(array()))      && p('1') && e('正常产品1'); // 步骤3：验证第一个产品名称

$config->systemMode = 'normal';
r($groupTest->getProductsForAdminGroupTest(array('1' => '项目集1'))) && p('1') && e('正常产品1'); // 步骤4：普通模式产品名称

$config->systemMode = 'ALM';
r($groupTest->getProductsForAdminGroupTest(array('1' => '项目集1'))) && p('1') && e('项目集1/正常产品1'); // 步骤5：ALM模式产品名称格式