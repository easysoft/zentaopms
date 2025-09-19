#!/usr/bin/env php
<?php

/**

title=测试 todoZen::buildCreateView();
timeout=0
cid=0

- 步骤1：使用当前日期属性date @2025-09-18
- 步骤2：使用过去日期属性date @2023-01-01
- 步骤3：使用未来日期属性date @2030-12-31
- 步骤4：使用特殊日期格式属性date @2024-09-18
- 步骤5：使用today字符串属性date @2025-09-18

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,test1,test2,test3,test4,test5,test6');
$user->password->range('123456{10}');
$user->realname->range('管理员,用户1,用户2,用户3,测试用户1,测试用户2,测试用户3,测试用户4,测试用户5,测试用户6');
$user->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$todoTest = new todoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($todoTest->buildCreateViewTest('2025-09-18')) && p('date') && e('2025-09-18'); // 步骤1：使用当前日期
r($todoTest->buildCreateViewTest('2023-01-01')) && p('date') && e('2023-01-01'); // 步骤2：使用过去日期
r($todoTest->buildCreateViewTest('2030-12-31')) && p('date') && e('2030-12-31'); // 步骤3：使用未来日期
r($todoTest->buildCreateViewTest('2024/09/18')) && p('date') && e('2024-09-18'); // 步骤4：使用特殊日期格式
r($todoTest->buildCreateViewTest('today')) && p('date') && e('2025-09-18'); // 步骤5：使用today字符串