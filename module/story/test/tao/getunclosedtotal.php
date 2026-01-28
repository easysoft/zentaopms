#!/usr/bin/env php
<?php

/**

title=测试 storyTao::getUnClosedTotal();
timeout=0
cid=18650

- 步骤1：正常获取story类型统计属性1 @2
- 步骤2：正常获取requirement类型统计属性1 @1
- 步骤3：正常获取epic类型统计 @0
- 步骤4：测试空字符串参数 @0
- 步骤5：测试无效类型参数 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('story');
$table->id->range('1-10');
$table->product->range('1-3');
$table->type->range('story{5},requirement{3},epic{2}');
$table->status->range('active{4},draft{2},reviewing{2},closed{2}');
$table->deleted->range('0{9},1{1}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($storyTest->getUnClosedTotalTest('story')) && p('1') && e('2'); // 步骤1：正常获取story类型统计
r($storyTest->getUnClosedTotalTest('requirement')) && p('1') && e('1'); // 步骤2：正常获取requirement类型统计
r($storyTest->getUnClosedTotalTest('epic')) && p() && e('0'); // 步骤3：正常获取epic类型统计
r($storyTest->getUnClosedTotalTest('')) && p() && e('0'); // 步骤4：测试空字符串参数
r($storyTest->getUnClosedTotalTest('invalid')) && p() && e('0'); // 步骤5：测试无效类型参数