#!/usr/bin/env php
<?php

/**

title=测试 projectZen::checkProductNameUnqiue();
timeout=0
cid=17936

- 执行projectzenTest模块的checkProductNameUnqiueTest方法，参数是$project, $rawdata  @1
- 执行projectzenTest模块的checkProductNameUnqiueTest方法，参数是$project, $rawdata 属性productName @『产品名称』不能为空。
- 执行projectzenTest模块的checkProductNameUnqiueTest方法，参数是$project, $rawdata 属性productName @产品名称已存在。
- 执行projectzenTest模块的checkProductNameUnqiueTest方法，参数是$project, $rawdata  @1
- 执行projectzenTest模块的checkProductNameUnqiueTest方法，参数是$project, $rawdata  @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-10');
$table->program->range('1,2,1,2,1,2,1,2,1,2');
$table->name->range('产品A,产品B,产品C,产品D,产品E,产品F,产品G,产品H,产品I,产品J');
$table->deleted->range('0{10}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectzenTest = new projectzenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 步骤1：项目无产品且无新产品标记
$project = new stdClass();
$project->hasProduct = false;
$rawdata = new stdClass();
r($projectzenTest->checkProductNameUnqiueTest($project, $rawdata)) && p() && e(1);

// 步骤2：项目有产品且有新产品标记但产品名为空
$project = new stdClass();
$project->hasProduct = true;
$project->parent = 1;
$rawdata = new stdClass();
$rawdata->newProduct = true;
$rawdata->productName = '';
r($projectzenTest->checkProductNameUnqiueTest($project, $rawdata)) && p('productName') && e('『产品名称』不能为空。');

// 步骤3：项目有产品且有新产品标记但产品名已存在
$project = new stdClass();
$project->hasProduct = true;
$project->parent = 1;
$rawdata = new stdClass();
$rawdata->newProduct = true;
$rawdata->productName = '产品A';
r($projectzenTest->checkProductNameUnqiueTest($project, $rawdata)) && p('productName') && e('产品名称已存在。');

// 步骤4：项目有产品且有新产品标记产品名合法唯一
$project = new stdClass();
$project->hasProduct = true;
$project->parent = 1;
$rawdata = new stdClass();
$rawdata->newProduct = true;
$rawdata->productName = '新产品X';
r($projectzenTest->checkProductNameUnqiueTest($project, $rawdata)) && p() && e(1);

// 步骤5：项目有产品但无新产品标记
$project = new stdClass();
$project->hasProduct = true;
$project->parent = 1;
$rawdata = new stdClass();
r($projectzenTest->checkProductNameUnqiueTest($project, $rawdata)) && p() && e(1);