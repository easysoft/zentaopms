#!/usr/bin/env php
<?php

/**

title=测试 editorModel::newControl();
timeout=0
cid=16241

- 测试步骤1：使用默认参数生成todo模块create方法的控制器代码
 - 属性hasPhpTag @1
 - 属性extendsControl @1
 - 属性hasMethodDef @1
 - 属性classNameCorrect @1
 - 属性isValidSyntax @1
- 测试步骤2：测试user模块profile方法的控制器代码生成
 - 属性hasPhpTag @1
 - 属性extendsControl @1
 - 属性hasMethodDef @1
 - 属性isValidSyntax @1
- 测试步骤3：测试包含下划线和数字的方法名生成
 - 属性hasPhpTag @1
 - 属性extendsControl @1
 - 属性hasMethodDef @1
 - 属性isValidSyntax @1
- 测试步骤4：测试空方法名的处理
 - 属性hasPhpTag @1
 - 属性extendsControl @1
 - 属性hasMethodDef @1
 - 属性isValidSyntax @1
- 测试步骤5：测试复杂路径的控制器代码生成
 - 属性hasPhpTag @1
 - 属性extendsControl @1
 - 属性hasMethodDef @1
 - 属性isValidSyntax @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/editor.unittest.class.php';

su('admin');

$editor = new editorTest();

r($editor->newControlTest()) && p('hasPhpTag,extendsControl,hasMethodDef,classNameCorrect,isValidSyntax') && e('1,1,1,1,1');    // 测试步骤1：使用默认参数生成todo模块create方法的控制器代码
r($editor->newControlModuleTest('user', 'profile')) && p('hasPhpTag,extendsControl,hasMethodDef,isValidSyntax') && e('1,1,1,1');    // 测试步骤2：测试user模块profile方法的控制器代码生成
r($editor->newControlSpecialMethodTest()) && p('hasPhpTag,extendsControl,hasMethodDef,isValidSyntax') && e('1,1,1,1');    // 测试步骤3：测试包含下划线和数字的方法名生成
r($editor->newControlEmptyMethodTest()) && p('hasPhpTag,extendsControl,hasMethodDef,isValidSyntax') && e('1,1,1,1');    // 测试步骤4：测试空方法名的处理
r($editor->newControlComplexPathTest()) && p('hasPhpTag,extendsControl,hasMethodDef,isValidSyntax') && e('1,1,1,1');    // 测试步骤5：测试复杂路径的控制器代码生成