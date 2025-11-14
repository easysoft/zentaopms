#!/usr/bin/env php
<?php

/**

title=测试 editorModel::printTree();
timeout=0
cid=16242

- 测试步骤1：正常文件结构转换
 - 属性isArray @1
 - 属性hasStructure @1
 - 属性hasValidItems @1
- 测试步骤2：空数组输入处理
 - 属性isArray @0
 - 属性isEmpty @1
- 测试步骤3：非数组输入处理
 - 属性hasError @1
 - 属性errorType @TypeError
- 测试步骤4：嵌套结构递归处理
 - 属性isArray @1
 - 属性itemCount @1
 - 属性hasStructure @1
- 测试步骤5：isRoot参数影响测试
 - 属性isArray @1
 - 属性hasStructure @1
 - 属性hasValidItems @1
- 测试步骤6：返回结构完整性验证
 - 属性hasText @1
 - 属性hasId @1
 - 属性hasActions @1
- 测试步骤7：模块文本本地化处理第0条的text属性 @待办

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/editor.unittest.class.php';

su('admin');

$editor = new editorTest();

r($editor->printTreeAdvancedTest()) && p('isArray,hasStructure,hasValidItems') && e('1,1,1');                    // 测试步骤1：正常文件结构转换
r($editor->printTreeEmptyTest()) && p('isArray,isEmpty') && e('0,1');                                            // 测试步骤2：空数组输入处理
r($editor->printTreeInvalidInputTest()) && p('hasError,errorType') && e('1,TypeError');                         // 测试步骤3：非数组输入处理
r($editor->printTreeNestedTest()) && p('isArray,itemCount,hasStructure') && e('1,1,1');                         // 测试步骤4：嵌套结构递归处理
r($editor->printTreeNonRootTest()) && p('isArray,hasStructure,hasValidItems') && e('1,1,1');                    // 测试步骤5：isRoot参数影响测试
r($editor->printTreeAdvancedTest()) && p('hasText,hasId,hasActions') && e('1,1,1');                             // 测试步骤6：返回结构完整性验证
r($editor->printTreeTest()) && p('0:text') && e('待办');                                                         // 测试步骤7：模块文本本地化处理