#!/usr/bin/env php
<?php

/**

title=测试 storyTao::buildReorderResult();
timeout=0
cid=18602

- 步骤1：正常嵌套数组
 - 属性1 @1
 - 属性2 @2
 - 属性3 @3
 - 属性4 @4
- 步骤2：空数组 @0
- 步骤3：单层数组
 - 属性10 @10
 - 属性20 @20
 - 属性30 @30
- 步骤4：深层嵌套
 - 属性1 @1
 - 属性2 @2
 - 属性3 @3
 - 属性4 @4
- 步骤5：字符串键
 - 属性a @a
 - 属性b @b
 - 属性c @c

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($storyTest->buildReorderResultTest(array(1 => array(2 => array(), 3 => array()), 4 => array()))) && p('1,2,3,4') && e('1,2,3,4'); // 步骤1：正常嵌套数组
r($storyTest->buildReorderResultTest(array())) && p() && e(0); // 步骤2：空数组
r($storyTest->buildReorderResultTest(array(10 => array(), 20 => array(), 30 => array()))) && p('10,20,30') && e('10,20,30'); // 步骤3：单层数组
r($storyTest->buildReorderResultTest(array(1 => array(2 => array(3 => array(4 => array())))))) && p('1,2,3,4') && e('1,2,3,4'); // 步骤4：深层嵌套
r($storyTest->buildReorderResultTest(array('a' => array('b' => array()), 'c' => array()))) && p('a,b,c') && e('a,b,c'); // 步骤5：字符串键