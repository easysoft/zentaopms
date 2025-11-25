#!/usr/bin/env php
<?php

/**

title=测试 userZen::prepareCustomFields();
timeout=0
cid=19678

- 步骤1：正常batchCreate方法测试，验证部门字段第listFields条的dept属性 @部门
- 步骤2：正常batchEdit方法测试，验证部门字段第listFields条的dept属性 @部门
- 步骤3：验证batchCreate显示字段第一个元素第showFields条的0属性 @dept
- 步骤4：验证batchEdit显示字段第一个元素第showFields条的0属性 @dept
- 步骤5：验证邮箱字段正确显示第listFields条的email属性 @邮箱

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$userTest = new userZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($userTest->prepareCustomFieldsTest('batchCreate', 'create')) && p('listFields:dept') && e('部门');                                      // 步骤1：正常batchCreate方法测试，验证部门字段
r($userTest->prepareCustomFieldsTest('batchEdit', 'edit')) && p('listFields:dept') && e('部门');                                        // 步骤2：正常batchEdit方法测试，验证部门字段
r($userTest->prepareCustomFieldsTest('batchCreate', 'create')) && p('showFields:0') && e('dept');                                       // 步骤3：验证batchCreate显示字段第一个元素
r($userTest->prepareCustomFieldsTest('batchEdit', 'edit')) && p('showFields:0') && e('dept');                                          // 步骤4：验证batchEdit显示字段第一个元素
r($userTest->prepareCustomFieldsTest('batchCreate', 'create')) && p('listFields:email') && e('邮箱');                                   // 步骤5：验证邮箱字段正确显示