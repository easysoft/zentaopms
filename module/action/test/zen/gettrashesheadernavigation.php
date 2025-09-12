#!/usr/bin/env php
<?php

/**

title=测试 actionZen::getTrashesHeaderNavigation();
timeout=0
cid=0

- 步骤1：空数组输入 @0
- 步骤2：包含有效对象类型
 - 属性user @user
 - 属性story @story
 - 属性task @task
 - 属性bug @bug
- 步骤3：包含无效对象类型
 - 属性user @user
 - 属性story @story
- 步骤4：大量对象类型返回计数 @11
- 步骤5：验证不同类型的处理
 - 属性program @program
 - 属性productline @productline

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$actionTest = new actionTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($actionTest->getTrashesHeaderNavigationTest(array())) && p() && e('0'); // 步骤1：空数组输入
r($actionTest->getTrashesHeaderNavigationTest(array('user', 'story', 'task', 'bug'))) && p('user,story,task,bug') && e('user,story,task,bug'); // 步骤2：包含有效对象类型
r($actionTest->getTrashesHeaderNavigationTest(array('invalidtype', 'user', 'story'))) && p('user,story') && e('user,story'); // 步骤3：包含无效对象类型
r(count($actionTest->getTrashesHeaderNavigationTest(array('user', 'story', 'task', 'bug', 'case', 'doc', 'program', 'product', 'productline', 'project', 'execution', 'extra1', 'extra2')))) && p() && e('11'); // 步骤4：大量对象类型返回计数
r($actionTest->getTrashesHeaderNavigationTest(array('program', 'productline'))) && p('program,productline') && e('program,productline'); // 步骤5：验证不同类型的处理