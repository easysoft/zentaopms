#!/usr/bin/env php
<?php

/**

title=测试 editorModel::getParam();
timeout=0
cid=16238

- 测试获取控制器方法参数（包含默认值）
 - 属性params @$date=\'today\'
- 测试获取模型方法参数（无默认值）属性params @$todo
- 测试获取不存在的方法参数属性hasError @1
- 测试不同数据类型的默认值处理属性params @$userID
- 测试带有多个参数的方法属性hasComma @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/editor.unittest.class.php';

su('admin');

$editor = new editorTest();

r($editor->getParamTest('todo', 'create', '')) && p('params') && e('$date=\'today\', $from=\'todo\'');                // 测试获取控制器方法参数（包含默认值）
r($editor->getParamTest('todo', 'create', 'Model')) && p('params') && e('$todo');                                   // 测试获取模型方法参数（无默认值）
r($editor->getParamTest('todo', 'nonExistentMethod', '')) && p('hasError') && e('1');                               // 测试获取不存在的方法参数
r($editor->getParamTest('user', 'view', '')) && p('params') && e('$userID');                                        // 测试不同数据类型的默认值处理
r($editor->getParamTest('todo', 'create', '')) && p('hasComma') && e('1');                                          // 测试带有多个参数的方法