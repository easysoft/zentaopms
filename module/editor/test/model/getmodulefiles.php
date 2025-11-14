#!/usr/bin/env php
<?php

/**

title=测试 editorModel::getModuleFiles();
timeout=0
cid=16237

- 测试获取todo模块的文件列表属性isArray @1
- 测试检查模块路径存在性属性hasModulePath @1
- 测试检查control.php文件属性hasControlFile @1
- 测试检查model.php文件属性hasModelFile @1
- 测试空模块名处理属性isArray @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/editor.unittest.class.php';

su('admin');

$editor = new editorTest();

r($editor->getModuleFilesTest('todo')) && p('isArray') && e('1');                                                              // 测试获取todo模块的文件列表
r($editor->getModuleFilesTest('todo')) && p('hasModulePath') && e('1');                                                        // 测试检查模块路径存在性
r($editor->getModuleFilesTest('todo')) && p('hasControlFile') && e('1');                                                       // 测试检查control.php文件
r($editor->getModuleFilesTest('todo')) && p('hasModelFile') && e('1');                                                         // 测试检查model.php文件
r($editor->getModuleFilesEmptyModuleTest()) && p('isArray') && e('1');                                                         // 测试空模块名处理