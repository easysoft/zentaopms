#!/usr/bin/env php
<?php

/**

title=测试 docZen::setAclForCreateLib();
timeout=0
cid=0

- 步骤1：custom类型保留private选项第doclibAclList条的private属性 @私有
- 步骤2：mine类型使用mySpaceAclList第doclibAclList条的private属性 @私有
- 步骤3：product类型修改default选项第doclibAclList条的default属性 @默认 产品 成员
- 步骤4：project类型修改private选项第doclibAclList条的private属性 @私有（仅 项目 相关人员可访问）
- 步骤5：execution类型修改private选项第doclibAclList条的private属性 @私有（仅 执行 相关人员可访问）

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 4. 测试步骤执行
r($docTest->setAclForCreateLibTest('custom')) && p('doclibAclList:private') && e('私有');                                 // 步骤1：custom类型保留private选项
r($docTest->setAclForCreateLibTest('mine')) && p('doclibAclList:private') && e('私有');                                   // 步骤2：mine类型使用mySpaceAclList
r($docTest->setAclForCreateLibTest('product')) && p('doclibAclList:default') && e('默认 产品 成员');                      // 步骤3：product类型修改default选项
r($docTest->setAclForCreateLibTest('project')) && p('doclibAclList:private') && e('私有（仅 项目 相关人员可访问）');       // 步骤4：project类型修改private选项
r($docTest->setAclForCreateLibTest('execution')) && p('doclibAclList:private') && e('私有（仅 执行 相关人员可访问）');     // 步骤5：execution类型修改private选项