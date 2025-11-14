#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getDocTemplateSpaces();
timeout=0
cid=19427

- 步骤1：测试正常调用获取文档模板空间属性1 @Doc Template Space
- 步骤2：测试返回数组长度 @1
- 步骤3：测试返回数组的键存在性 @1
- 步骤4：测试返回结果非空 @1
- 步骤5：测试获取ID为1的空间名称属性1 @Doc Template Space

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getDocTemplateSpacesTest()) && p('1') && e('Doc Template Space'); // 步骤1：测试正常调用获取文档模板空间
r(count($tutorialTest->getDocTemplateSpacesTest())) && p() && e('1'); // 步骤2：测试返回数组长度
r(array_key_exists(1, $tutorialTest->getDocTemplateSpacesTest())) && p() && e('1'); // 步骤3：测试返回数组的键存在性
r(!empty($tutorialTest->getDocTemplateSpacesTest())) && p() && e('1'); // 步骤4：测试返回结果非空
r($tutorialTest->getDocTemplateSpacesTest()) && p('1') && e('Doc Template Space'); // 步骤5：测试获取ID为1的空间名称