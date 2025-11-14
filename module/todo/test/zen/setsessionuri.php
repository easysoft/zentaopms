#!/usr/bin/env php
<?php

/**

title=测试 todoZen::setSessionUri();
timeout=0
cid=19307

- 步骤1：设置有效的URI @1
- 步骤2：设置空字符串URI @1
- 步骤3：设置带参数的URI @1
- 步骤4：设置特殊字符URI @1
- 步骤5：设置长URI字符串 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$todoTest = new todoTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($todoTest->setSessionUriTest('todo-browse-all-all-all.html')) && p() && e(1);  // 步骤1：设置有效的URI
r($todoTest->setSessionUriTest('')) && p() && e(1);                               // 步骤2：设置空字符串URI
r($todoTest->setSessionUriTest('todo-browse-all-all-all.html?param=value')) && p() && e(1); // 步骤3：设置带参数的URI
r($todoTest->setSessionUriTest('todo-browse-all-all-all.html#anchor')) && p() && e(1);      // 步骤4：设置特殊字符URI
r($todoTest->setSessionUriTest('very-long-uri-string-with-many-parameters-todo-browse-all-all-all.html?param1=value1&param2=value2&param3=value3')) && p() && e(1); // 步骤5：设置长URI字符串