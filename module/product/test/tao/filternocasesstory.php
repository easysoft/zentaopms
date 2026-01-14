#!/usr/bin/env php
<?php

/**

title=测试 productTao::filterNoCasesStory();
timeout=0
cid=17539

- 步骤1：正常情况，输入有用例的需求ID @5
- 步骤2：空数组输入 @0
- 步骤3：不存在的需求ID @0
- 步骤4：混合存在和不存在的需求ID @2
- 步骤5：包含无用例的需求ID @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1{10}');
$story->title->range('Story1,Story2,Story3,Story4,Story5,Story6,Story7,Story8,Story9,Story10');
$story->type->range('story{10}');
$story->deleted->range('0{10}');
$story->gen(10);

$case = zenData('case');
$case->id->range('1-7');
$case->story->range('1,2,3,4,5,4,5');
$case->product->range('1{7}');
$case->title->range('Case1,Case2,Case3,Case4,Case5,Case6,Case7');
$case->deleted->range('0{5},1{2}');
$case->gen(7);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTaoTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($productTest->filterNoCasesStoryTest(array(1, 2, 3, 4, 5))) && p('') && e('5'); // 步骤1：正常情况，输入有用例的需求ID
r($productTest->filterNoCasesStoryTest(array())) && p('') && e('0'); // 步骤2：空数组输入
r($productTest->filterNoCasesStoryTest(array(100, 200))) && p('') && e('0'); // 步骤3：不存在的需求ID
r($productTest->filterNoCasesStoryTest(array(1, 2, 6, 7))) && p('') && e('2'); // 步骤4：混合存在和不存在的需求ID
r($productTest->filterNoCasesStoryTest(array(1, 2, 3, 4, 5, 6))) && p('') && e('5'); // 步骤5：包含无用例的需求ID