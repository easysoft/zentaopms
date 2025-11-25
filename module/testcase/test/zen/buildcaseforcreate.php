#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::buildCaseForCreate();
timeout=0
cid=19076

- 步骤1：从project来源创建
 - 属性status @normal
 - 属性type @feature
- 步骤2：从bug来源创建属性fromBug @123
- 步骤3：从execution来源创建属性execution @1
- 步骤4：自动化用例测试
 - 属性auto @auto
 - 属性script @test script
- 步骤5：故事版本测试属性storyVersion @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->shadow->range('0{4},1{1}');
$product->gen(5);

$story = zenData('story');
$story->id->range('1-3');
$story->version->range('1-3');
$story->title->range('需求1,需求2,需求3');
$story->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcasezenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->buildCaseForCreateTest('project', 1)) && p('status,type') && e('normal,feature'); // 步骤1：从project来源创建
r($testcaseTest->buildCaseForCreateTest('bug', 123)) && p('fromBug') && e('123'); // 步骤2：从bug来源创建
r($testcaseTest->buildCaseForCreateTest('execution', 1)) && p('execution') && e('1'); // 步骤3：从execution来源创建
r($testcaseTest->buildCaseForCreateTest('', 0, 1, 1)) && p('auto,script') && e('auto,test script'); // 步骤4：自动化用例测试
r($testcaseTest->buildCaseForCreateTest('', 0, 1, 0, 1)) && p('storyVersion') && e('1'); // 步骤5：故事版本测试