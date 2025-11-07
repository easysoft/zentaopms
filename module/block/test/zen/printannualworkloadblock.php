#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printAnnualWorkloadBlock();
timeout=0
cid=0

- 步骤1:测试方法执行成功返回success为true属性success @1
- 步骤2:测试返回maxStoryEstimate最大需求规模属性maxStoryEstimate @80
- 步骤3:测试返回maxStoryCount最大需求数属性maxStoryCount @40
- 步骤4:测试返回maxBugCount最大Bug数属性maxBugCount @15
- 步骤5:测试返回对象包含所有必要属性
 - 属性success @1
 - 属性maxStoryEstimate @80

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品A,产品B,产品C,产品D,产品E');
$product->code->range('producta,productb,productc,productd,producte');
$product->status->range('normal{5}');
$product->deleted->range('0{5}');
$product->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($blockTest->printAnnualWorkloadBlockTest()) && p('success') && e('1'); // 步骤1:测试方法执行成功返回success为true
r($blockTest->printAnnualWorkloadBlockTest()) && p('maxStoryEstimate') && e('80'); // 步骤2:测试返回maxStoryEstimate最大需求规模
r($blockTest->printAnnualWorkloadBlockTest()) && p('maxStoryCount') && e('40'); // 步骤3:测试返回maxStoryCount最大需求数
r($blockTest->printAnnualWorkloadBlockTest()) && p('maxBugCount') && e('15'); // 步骤4:测试返回maxBugCount最大Bug数
r($blockTest->printAnnualWorkloadBlockTest()) && p('success,maxStoryEstimate') && e('1,80'); // 步骤5:测试返回对象包含所有必要属性