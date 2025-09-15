#!/usr/bin/env php
<?php

/**

title=测试 mailModel::setErrorLang();
timeout=0
cid=0

- 步骤1：测试默认语言环境下设置错误语言属性processed @1
- 步骤2：测试获取当前语言属性currentLang @zh-cn
- 步骤3：测试方法正常执行属性processed @1
- 步骤4：测试多次调用稳定性属性processed @1
- 步骤5：测试综合验证
 - 属性processed @1
 - 属性currentLang @zh-cn

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$mailTest = new mailTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($mailTest->setErrorLangTest()) && p('processed') && e('1'); // 步骤1：测试默认语言环境下设置错误语言
r($mailTest->setErrorLangTest()) && p('currentLang') && e('zh-cn'); // 步骤2：测试获取当前语言
r($mailTest->setErrorLangTest()) && p('processed') && e('1'); // 步骤3：测试方法正常执行
r($mailTest->setErrorLangTest()) && p('processed') && e('1'); // 步骤4：测试多次调用稳定性
r($mailTest->setErrorLangTest()) && p('processed,currentLang') && e('1,zh-cn'); // 步骤5：测试综合验证