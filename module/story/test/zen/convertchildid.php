#!/usr/bin/env php
<?php

/**

title=测试 storyZen::convertChildID();
timeout=0
cid=0

- 执行storyTest模块的convertChildIDTest方法，参数是array 
 -  @1
 - 属性1 @2
 - 属性2 @3
 - 属性3 @4
 - 属性4 @5
- 执行storyTest模块的convertChildIDTest方法，参数是array 
 -  @1
 - 属性1 @2
 - 属性2 @3
- 执行storyTest模块的convertChildIDTest方法，参数是array 
 -  @1
 - 属性1 @5
 - 属性2 @3
 - 属性3 @6
- 执行storyTest模块的convertChildIDTest方法，参数是array 
 -  @1
 - 属性1 @2
 - 属性2 @3
- 执行storyTest模块的convertChildIDTest方法，参数是array 
 -  @999
 - 属性1 @1
 - 属性2 @123
 - 属性3 @def

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$storyTest = new storyTest();

// 4. 强制要求：必须包含至少5个测试步骤

// 步骤1：正常需求ID列表处理
r($storyTest->convertChildIDTest(array(1, 2, 3, 4, 5))) && p('0,1,2,3,4') && e('1,2,3,4,5');

// 步骤2：包含子需求ID的列表处理（格式：父ID-子ID）
r($storyTest->convertChildIDTest(array('10-1', '20-2', '30-3'))) && p('0,1,2') && e('1,2,3');

// 步骤3：混合格式ID列表处理（包含普通ID和子需求ID）
r($storyTest->convertChildIDTest(array(1, '10-5', 3, '20-6'))) && p('0,1,2,3') && e('1,5,3,6');

// 步骤4：空值和重复ID处理
r($storyTest->convertChildIDTest(array(1, 2, '', 0, 2, null, 3, 1))) && p('0,1,2') && e('1,2,3');

// 步骤5：边界情况和特殊格式处理（包含负数、字符串等）
r($storyTest->convertChildIDTest(array('100-999', '-1', '0-123', 'abc-def'))) && p('0,1,2,3') && e('999,1,123,def');