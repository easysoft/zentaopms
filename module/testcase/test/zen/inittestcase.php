#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::initTestcase();
timeout=0
cid=19100

- 步骤1：基础初始化测试
 - 属性type @feature
 - 属性pri @3
 - 属性scene @0
 - 属性story @0
- 步骤2：从测试用例模板创建
 - 属性title @测试用例1
 - 属性type @feature
 - 属性pri @1
 - 属性story @1
- 步骤3：从Bug模板创建
 - 属性title @Bug标题1
 - 属性type @feature
 - 属性pri @2
 - 属性story @2
- 步骤4：Story关联测试属性story @3
- 步骤5：复合条件测试（Bug优先）
 - 属性title @Bug标题2
 - 属性type @feature
 - 属性pri @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$case = zenData('case');
$case->id->range('1-5');
$case->product->range('1');
$case->title->range('测试用例1,测试用例2,测试用例3,测试用例4,测试用例5');
$case->type->range('feature');
$case->pri->range('1-3');
$case->story->range('1-5');
$case->stage->range('unittest');
$case->precondition->range('前置条件1,前置条件2,前置条件3,前置条件4,前置条件5');
$case->keywords->range('关键词1,关键词2,关键词3,关键词4,关键词5');
$case->gen(5);

$bug = zenData('bug');
$bug->id->range('1-3');
$bug->product->range('1');
$bug->title->range('Bug标题1,Bug标题2,Bug标题3');
$bug->type->range('codeerror');
$bug->pri->range('2');
$bug->severity->range('3');
$bug->story->range('2-4');
$bug->keywords->range('Bug关键词1,Bug关键词2,Bug关键词3');
$bug->steps->range('Bug重现步骤1,Bug重现步骤2,Bug重现步骤3');
$bug->gen(3);

$story = zenData('story');
$story->id->range('1-5');
$story->product->range('1');
$story->title->range('需求1,需求2,需求3,需求4,需求5');
$story->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->initTestcaseTest(0, 0, 0)) && p('type,pri,scene,story') && e('feature,3,0,0'); // 步骤1：基础初始化测试
r($testcaseTest->initTestcaseTest(0, 1, 0)) && p('title,type,pri,story') && e('测试用例1,feature,1,1'); // 步骤2：从测试用例模板创建
r($testcaseTest->initTestcaseTest(0, 0, 1)) && p('title,type,pri,story') && e('Bug标题1,feature,2,2'); // 步骤3：从Bug模板创建
r($testcaseTest->initTestcaseTest(3, 0, 0)) && p('story') && e('3'); // 步骤4：Story关联测试
r($testcaseTest->initTestcaseTest(0, 2, 2)) && p('title,type,pri') && e('Bug标题2,feature,2'); // 步骤5：复合条件测试（Bug优先）
