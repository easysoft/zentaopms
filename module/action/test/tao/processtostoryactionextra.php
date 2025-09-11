#!/usr/bin/env php
<?php

/**

title=测试 actionTao::processToStoryActionExtra();
timeout=0
cid=0

- 检查有内容第extra条的strlen属性 @>0
- 执行actionTest模块的processToStoryActionExtraTest方法，参数是$action2 属性extra @
- 检查有内容第extra条的strlen属性 @>0
- 执行actionTest模块的processToStoryActionExtraTest方法，参数是$action4 属性extra @
- 检查有内容第extra条的strlen属性 @>0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

// 2. zendata数据准备
zendata('product')->loadYaml('zt_product_processtostoryactionextra', false, 2)->gen(5);
zendata('story')->loadYaml('zt_story_processtostoryactionextra', false, 2)->gen(5);
zendata('projectstory')->loadYaml('zt_projectstory_processtostoryactionextra', false, 2)->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$actionTest = new actionTest();

// 5. 测试步骤
// 步骤1：非影子产品，有故事标题的情况
$action1 = new stdClass();
$action1->product = '1';
$action1->extra = '1';
r($actionTest->processToStoryActionExtraTest($action1)) && p('extra:strlen') && e('>0'); // 检查有内容

// 步骤2：影子产品，有项目故事关联的情况
$action2 = new stdClass();
$action2->product = '4';
$action2->extra = '1';
r($actionTest->processToStoryActionExtraTest($action2)) && p('extra') && e('');

// 步骤3：影子产品，无项目故事关联的情况
$action3 = new stdClass();
$action3->product = '5';
$action3->extra = '1';
r($actionTest->processToStoryActionExtraTest($action3)) && p('extra:strlen') && e('>0'); // 检查有内容

// 步骤4：故事不存在的情况
$action4 = new stdClass();
$action4->product = '1';
$action4->extra = '999';
r($actionTest->processToStoryActionExtraTest($action4)) && p('extra') && e('');

// 步骤5：产品不存在的情况
$action5 = new stdClass();
$action5->product = '999';
$action5->extra = '4';
r($actionTest->processToStoryActionExtraTest($action5)) && p('extra:strlen') && e('>0'); // 检查有内容