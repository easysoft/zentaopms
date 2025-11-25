#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::afterCreate();
timeout=0
cid=19058

- 步骤1：正常情况属性cookiesSet @1
- 步骤2：包含文件列表
 - 属性executed @1
 - 属性filesProcessed @1
- 步骤3：空文件列表属性filesProcessed @0
- 步骤4：无POST数据属性executed @1
- 步骤5：不同模块产品
 - 属性cookiesSet @1
 - 属性syncCalled @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$case = zenData('case');
$case->id->range('1-10');
$case->product->range('1-3');
$case->module->range('1-5');
$case->scene->range('1-3');
$case->story->range('1-5');
$case->title->range('测试用例{1-10}');
$case->type->range('feature,performance,config,install');
$case->status->range('normal,wait,blocked');
$case->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->afterCreateTest((object)array('product' => 1, 'module' => 2, 'scene' => 3), 1)) && p('cookiesSet') && e('1'); // 步骤1：正常情况
r($testcaseTest->afterCreateTest((object)array('product' => 1, 'module' => 2, 'scene' => 3), 2, '["file1.txt","file2.txt"]')) && p('executed,filesProcessed') && e('1,1'); // 步骤2：包含文件列表
r($testcaseTest->afterCreateTest((object)array('product' => 1, 'module' => 2, 'scene' => 3), 3, '')) && p('filesProcessed') && e('0'); // 步骤3：空文件列表
r($testcaseTest->afterCreateTest((object)array('product' => 2, 'module' => 1, 'scene' => 0), 4)) && p('executed') && e('1'); // 步骤4：无POST数据
r($testcaseTest->afterCreateTest((object)array('product' => 3, 'module' => 5, 'scene' => 2), 5)) && p('cookiesSet,syncCalled') && e('1,1'); // 步骤5：不同模块产品