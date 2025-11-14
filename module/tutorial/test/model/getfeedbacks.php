#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getFeedbacks();
timeout=0
cid=19437

- 测试获取反馈1的信息
 - 第1条的id属性 @1
 - 第1条的title属性 @Wait feedback
 - 第1条的status属性 @wait
 - 第1条的product属性 @1
 - 第1条的openedBy属性 @admin
- 测试获取反馈2的信息
 - 第2条的id属性 @2
 - 第2条的title属性 @Not review feedback
 - 第2条的status属性 @noreview
 - 第2条的product属性 @1
 - 第2条的openedBy属性 @admin
- 测试反馈1的其他属性
 - 第1条的pri属性 @3
 - 第1条的public属性 @1
 - 第1条的notify属性 @1
- 测试反馈2的其他属性
 - 第2条的pri属性 @3
 - 第2条的public属性 @1
 - 第2条的notify属性 @1
- 测试反馈1的状态属性
 - 第1条的deleted属性 @0
 - 第1条的dept属性 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getFeedbacksTest()) && p('1:id,title,status,product,openedBy') && e('1,Wait feedback,wait,1,admin'); // 测试获取反馈1的信息
r($tutorialTest->getFeedbacksTest()) && p('2:id,title,status,product,openedBy') && e('2,Not review feedback,noreview,1,admin'); // 测试获取反馈2的信息
r($tutorialTest->getFeedbacksTest()) && p('1:pri,public,notify') && e('3,1,1'); // 测试反馈1的其他属性
r($tutorialTest->getFeedbacksTest()) && p('2:pri,public,notify') && e('3,1,1'); // 测试反馈2的其他属性
r($tutorialTest->getFeedbacksTest()) && p('1:deleted,dept') && e('0,0'); // 测试反馈1的状态属性