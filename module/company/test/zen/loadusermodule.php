#!/usr/bin/env php
<?php

/**

title=测试 companyZen::loadUserModule();
timeout=0
cid=15741

- 步骤1:传入有效用户ID 1,验证返回数组的第一个元素为用户账号 @admin
- 步骤2:传入有效用户ID 2,验证返回数组的第一个元素为用户账号 @user1
- 步骤3:传入用户ID为0,验证返回数组的第一个元素为all @all
- 步骤4:传入有效用户ID 1,验证返回数组的第二个元素是数组类型 @1
- 步骤5:传入有效用户ID 1,验证返回数组的第二个元素包含空字符串键 @1

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备
zenData('user')->gen(10);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$companyTest = new companyZenTest();

// 5. 强制要求:必须包含至少5个测试步骤
r($companyTest->loadUserModuleTest(1)) && p('0') && e('admin'); // 步骤1:传入有效用户ID 1,验证返回数组的第一个元素为用户账号
r($companyTest->loadUserModuleTest(2)) && p('0') && e('user1'); // 步骤2:传入有效用户ID 2,验证返回数组的第一个元素为用户账号
r($companyTest->loadUserModuleTest(0)) && p('0') && e('all'); // 步骤3:传入用户ID为0,验证返回数组的第一个元素为all
r(is_array($companyTest->loadUserModuleTest(1)[1])) && p() && e('1'); // 步骤4:传入有效用户ID 1,验证返回数组的第二个元素是数组类型
r(isset($companyTest->loadUserModuleTest(1)[1][''])) && p() && e('1'); // 步骤5:传入有效用户ID 1,验证返回数组的第二个元素包含空字符串键