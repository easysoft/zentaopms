#!/usr/bin/env php
<?php

/**

title=测试 actionZen::getTrashesHeaderNavigation();
timeout=0
cid=0

- 执行actionTest模块的getTrashesHeaderNavigationTest方法，参数是array  @0
- 执行actionTest模块的getTrashesHeaderNavigationTest方法，参数是array 
 - 属性user @user
 - 属性story @story
 - 属性task @task
 - 属性bug @bug
- 执行actionTest模块的getTrashesHeaderNavigationTest方法，参数是array 
 - 属性user @user
 - 属性story @story
- 执行actionTest模块的getTrashesHeaderNavigationTest方法，参数是$manyTypes  @11
- 执行actionTest模块的getTrashesHeaderNavigationTest方法，参数是array  @4
- 执行actionTest模块的getTrashesHeaderNavigationTest方法，参数是array 
 - 属性user @user
 - 属性story @story
 - 属性build @build
 - 属性release @release
- 执行actionTest模块的getTrashesHeaderNavigationTest方法，参数是array  @2
- 执行actionTest模块的getTrashesHeaderNavigationTest方法，参数是$nonPreferredTypes  @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$actionTest = new actionTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：空数组输入
r($actionTest->getTrashesHeaderNavigationTest(array())) && p() && e('0');

// 步骤2：仅包含ALM模式下的首选类型（前4个）
r($actionTest->getTrashesHeaderNavigationTest(array('user', 'story', 'task', 'bug'))) && p('user,story,task,bug') && e('user,story,task,bug');

// 步骤3：包含无效对象类型（会被过滤）
r($actionTest->getTrashesHeaderNavigationTest(array('invalidtype', 'user', 'story', 'notexist'))) && p('user,story') && e('user,story');

// 步骤4：大量对象类型（超过首选数量限制10个）
$manyTypes = array('user', 'story', 'task', 'bug', 'case', 'doc', 'program', 'product', 'productline', 'project', 'execution', 'build', 'release');
r(count($actionTest->getTrashesHeaderNavigationTest($manyTypes))) && p() && e('11');

// 步骤5：light模式下的首选类型验证
global $tester;
$originalMode = isset($tester->config->systemMode) ? $tester->config->systemMode : 'ALM';
$tester->config->systemMode = 'light';
r(count($actionTest->getTrashesHeaderNavigationTest(array('user', 'story', 'program', 'productline')))) && p() && e('4');
$tester->config->systemMode = $originalMode;

// 步骤6：混合首选和非首选类型（首选类型优先显示）
r($actionTest->getTrashesHeaderNavigationTest(array('build', 'user', 'story', 'release'))) && p('user,story,build,release') && e('user,story,build,release');

// 步骤7：非首选类型填充到首选数量（不足10个时用非首选类型填充）
r(count($actionTest->getTrashesHeaderNavigationTest(array('build', 'release')))) && p() && e('2');

// 步骤8：仅非首选类型输入（测试数量）
$nonPreferredTypes = array('build', 'release', 'trainplan');
r(count($actionTest->getTrashesHeaderNavigationTest($nonPreferredTypes))) && p() && e('2');