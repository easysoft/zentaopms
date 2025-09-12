#!/usr/bin/env php
<?php

/**

title=测试 searchTao::unify();
timeout=0
cid=0

- 步骤1：测试下划线替换 @hello,world

- 步骤2：测试多种特殊符号 @test,hello,world,test,line,end,symbol,more,data,back,plus,star,slash,back,dot,comma

- 步骤3：测试连续特殊符号去重 @multiple,commas,spaces,dashes

- 步骤4：测试自定义分隔符 @custom|separator
- 步骤5：测试空字符串 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$searchTest = new searchTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($searchTest->unifyTest('hello_world')) && p() && e('hello,world'); // 步骤1：测试下划线替换
r($searchTest->unifyTest('test、hello world-test\nline?end@symbol&more%data~back`plus+star*slash/back\\dot。comma，')) && p() && e('test,hello,world,test,line,end,symbol,more,data,back,plus,star,slash,back,dot,comma'); // 步骤2：测试多种特殊符号
r($searchTest->unifyTest('multiple___commas、、、spaces   dashes---')) && p() && e('multiple,commas,spaces,dashes'); // 步骤3：测试连续特殊符号去重
r($searchTest->unifyTest('custom_separator', '|')) && p() && e('custom|separator'); // 步骤4：测试自定义分隔符
r($searchTest->unifyTest('')) && p() && e(0); // 步骤5：测试空字符串