#!/usr/bin/env php
<?php

/**

title=测试 projectZen::prepareProject();
timeout=0
cid=0

- 执行projectTest模块的prepareProjectTest方法，参数是$testData1, 1
 - 属性name @测试项目1
 - 属性budget @15000.57
- 执行projectTest模块的prepareProjectTest方法，参数是$testData2, 0
 - 属性name @测试项目2
 - 属性team @测试项目2
- 执行projectTest模块的prepareProjectTest方法，参数是$testData3, 1 属性products[0] @请关联产品或创建产品。
- 执行projectTest模块的prepareProjectTest方法，参数是$testData4, 0 属性days @0
- 执行projectTest模块的prepareProjectTest方法，参数是$testData5, 0 属性budget @25000.79

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备
$table = zenData('project');
$table->id->range('1-5');
$table->name->range('项目1,项目2,项目3,项目4,项目5');
$table->parent->range('1,2,3,4,5');
$table->begin->range('`2024-01-01`,`2024-02-01`,`2024-03-01`,`2024-04-01`,`2024-05-01`');
$table->end->range('`2024-12-31`,`2024-11-30`,`2024-10-31`,`2024-09-30`,`2024-08-31`');
$table->days->range('100,200,150,180,120');
$table->budget->range('10000,20000,30000,40000,50000');
$table->status->range('wait,doing,closed,suspended,wait');
$table->hasProduct->range('1,0,1,1,0');
$table->type->range('project');
$table->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$projectTest = new projectZenTest();

// 5. 测试步骤 - 必须包含至少5个测试步骤

// 步骤1：有产品项目的正常数据准备
$testData1 = array(
    'name' => '测试项目1',
    'budget' => '15000.567',
    'begin' => '2024-01-01',
    'end' => '2024-12-31',
    'days' => '200',
    'parent' => 1,
    'products' => array(1, 2),
    'branch' => array(array(0), array(0))
);

r($projectTest->prepareProjectTest($testData1, 1)) && p('name,budget') && e('测试项目1,15000.57');

// 步骤2：无产品项目的正常数据准备
$testData2 = array(
    'name' => '测试项目2',
    'budget' => '20000'
);

r($projectTest->prepareProjectTest($testData2, 0)) && p('name,team') && e('测试项目2,测试项目2');

// 步骤3：有产品项目但产品列表为空的情况
$testData3 = array(
    'name' => '测试项目3',
    'products' => array(''),
    'branch' => array(array(''))
);
global $app;
$app->rawMethod = 'create';

r($projectTest->prepareProjectTest($testData3, 1)) && p('products[0]') && e('请关联产品或创建产品。');

// 步骤4：长期项目的数据处理
$testData4 = array(
    'name' => '测试项目4',
    'longTime' => true,
    'delta' => '999'
);

r($projectTest->prepareProjectTest($testData4, 0)) && p('days') && e('0');

// 步骤5：预算字段的数据处理和格式化
$testData5 = array(
    'name' => '测试项目5',
    'budget' => '25000.789',
    'future' => false
);

r($projectTest->prepareProjectTest($testData5, 0)) && p('budget') && e('25000.79');