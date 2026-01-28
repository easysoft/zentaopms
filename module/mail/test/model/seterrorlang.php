#!/usr/bin/env php
<?php

/**

title=测试 mailModel::setErrorLang();
timeout=0
cid=17020

- 步骤1：测试方法正常执行属性processed @1
- 步骤2：测试MTA对象存在性属性mtaExists @1
- 步骤3：测试当前语言获取属性currentLang @zh-cn
- 步骤4：测试多次调用稳定性属性processed @1
- 步骤5：测试综合验证
 - 属性processed @1
 - 属性mtaExists @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$mailTest = new mailModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($mailTest->setErrorLangTest()) && p('processed') && e('1'); // 步骤1：测试方法正常执行
r($mailTest->setErrorLangTest()) && p('mtaExists') && e('1'); // 步骤2：测试MTA对象存在性
r($mailTest->setErrorLangTest()) && p('currentLang') && e('zh-cn'); // 步骤3：测试当前语言获取
r($mailTest->setErrorLangTest()) && p('processed') && e('1'); // 步骤4：测试多次调用稳定性
r($mailTest->setErrorLangTest()) && p('processed,mtaExists') && e('1,1'); // 步骤5：测试综合验证