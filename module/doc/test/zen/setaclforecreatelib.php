#!/usr/bin/env php
<?php

/**

title=测试 docZen::setAclForCreateLib();
timeout=0
cid=0

- 步骤1：测试custom类型，期望设置自定义API访问控制第apiAclList条的default属性 @默认 自定义 成员
- 步骤2：测试mine类型，期望使用mySpaceAclList第doclibAclList条的private属性 @私有
- 步骤3：测试product类型，期望修改default选项第doclibAclList条的default属性 @默认 产品 成员
- 步骤4：测试project类型，期望修改private选项第doclibAclList条的private属性 @私有（仅 项目 相关人员可访问）
- 步骤5：测试execution类型，期望修改private选项第doclibAclList条的private属性 @私有（仅 执行 相关人员可访问）
- 步骤6：测试api类型，期望设置api aclList第apiAclList条的default属性 @默认 API 成员
- 步骤7：测试无效类型，期望设置api aclList第apiAclList条的default属性 @默认 invalid 成员

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->setAclForCreateLibTest('custom')) && p('apiAclList:default') && e('默认 自定义 成员'); // 步骤1：测试custom类型，期望设置自定义API访问控制
r($docTest->setAclForCreateLibTest('mine')) && p('doclibAclList:private') && e('私有'); // 步骤2：测试mine类型，期望使用mySpaceAclList
r($docTest->setAclForCreateLibTest('product')) && p('doclibAclList:default') && e('默认 产品 成员'); // 步骤3：测试product类型，期望修改default选项
r($docTest->setAclForCreateLibTest('project')) && p('doclibAclList:private') && e('私有（仅 项目 相关人员可访问）'); // 步骤4：测试project类型，期望修改private选项
r($docTest->setAclForCreateLibTest('execution')) && p('doclibAclList:private') && e('私有（仅 执行 相关人员可访问）'); // 步骤5：测试execution类型，期望修改private选项
r($docTest->setAclForCreateLibTest('api')) && p('apiAclList:default') && e('默认 API 成员'); // 步骤6：测试api类型，期望设置api aclList
r($docTest->setAclForCreateLibTest('invalid')) && p('apiAclList:default') && e('默认 invalid 成员'); // 步骤7：测试无效类型，期望设置api aclList