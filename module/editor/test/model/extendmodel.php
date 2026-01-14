#!/usr/bin/env php
<?php

/**

title=测试 editorModel::extendModel();
timeout=0
cid=16231

- 步骤1：正常情况-todo模块create方法扩展
 - 属性hasPhpTag @1
 - 属性hasMethodSignature @1
 - 属性hasParentCall @1
- 步骤2：边界值-编辑方法扩展
 - 属性hasPhpTag @1
 - 属性hasMethodName @1
- 步骤3：异常输入-验证parent调用属性hasParentCall @1
- 步骤4：权限验证-语法结构验证属性hasValidSyntax @1
- 步骤5：业务规则-参数处理验证属性hasCorrectParams @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$editor = new editorModelTest();

// 4. 执行测试步骤（至少5个）
r($editor->extendModelCreateTest()) && p('hasPhpTag,hasMethodSignature,hasParentCall') && e('1,1,1');         // 步骤1：正常情况-todo模块create方法扩展
r($editor->extendModelEditTest()) && p('hasPhpTag,hasMethodName') && e('1,1');                                 // 步骤2：边界值-编辑方法扩展
r($editor->extendModelParentCallTest()) && p('hasParentCall') && e('1');                                      // 步骤3：异常输入-验证parent调用
r($editor->extendModelSyntaxTest()) && p('hasValidSyntax') && e('1');                                        // 步骤4：权限验证-语法结构验证
r($editor->extendModelParameterTest()) && p('hasCorrectParams') && e('1');                                   // 步骤5：业务规则-参数处理验证