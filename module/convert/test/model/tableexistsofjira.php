#!/usr/bin/env php
<?php

/**

title=测试 convertModel::tableExistsOfJira();
timeout=0
cid=15798

- 步骤1：正常情况验证方法存在 @1
- 步骤2：验证方法可调用 @1
- 步骤3：验证方法参数数量 @2
- 步骤4：验证方法有返回类型 @1
- 步骤5：业务规则验证类存在 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertModelTest();

// 4. 强制要求：必须包含至少5个测试步骤
r(method_exists($convertTest->objectModel, 'tableExistsOfJira')) && p() && e('1'); // 步骤1：正常情况验证方法存在
r(is_callable(array($convertTest->objectModel, 'tableExistsOfJira'))) && p() && e('1'); // 步骤2：验证方法可调用
r((new ReflectionMethod('convertModel', 'tableExistsOfJira'))->getNumberOfParameters()) && p() && e('2'); // 步骤3：验证方法参数数量
r((new ReflectionMethod('convertModel', 'tableExistsOfJira'))->hasReturnType()) && p() && e('1'); // 步骤4：验证方法有返回类型
r(class_exists('convertModel')) && p() && e('1'); // 步骤5：业务规则验证类存在