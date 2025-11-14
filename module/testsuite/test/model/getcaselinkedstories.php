#!/usr/bin/env php
<?php

/**

title=测试 testsuiteModel::getCaseLinkedStories();
timeout=0
cid=19141

- 步骤1：正常情况，产品1下关联的需求
 - 属性1 @需求1
 - 属性2 @需求2
- 步骤2：产品2下关联的需求属性3 @需求3
- 步骤3：不存在的产品ID @0
- 步骤4：产品ID为0的边界情况 @0
- 步骤5：负数产品ID的异常情况 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testsuite.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$case = zenData('case');
$case->id->range('1-10');
$case->product->range('1{5},2{3},3{2}');
$case->story->range('1{2},2{1},0{2},3{1},4{1},0{3}');
$case->deleted->range('0{9},1{1}');
$case->gen(10);

$story = zenData('story');
$story->id->range('1-5');
$story->title->range('需求1,需求2,需求3,需求4,需求5');
$story->deleted->range('0');
$story->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testsuiteTest = new testsuiteTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($testsuiteTest->getCaseLinkedStoriesTest(1)) && p('1,2') && e('需求1,需求2'); // 步骤1：正常情况，产品1下关联的需求
r($testsuiteTest->getCaseLinkedStoriesTest(2)) && p('3') && e('需求3'); // 步骤2：产品2下关联的需求
r($testsuiteTest->getCaseLinkedStoriesTest(999)) && p() && e('0'); // 步骤3：不存在的产品ID
r($testsuiteTest->getCaseLinkedStoriesTest(0)) && p() && e('0'); // 步骤4：产品ID为0的边界情况
r($testsuiteTest->getCaseLinkedStoriesTest(-1)) && p() && e('0'); // 步骤5：负数产品ID的异常情况