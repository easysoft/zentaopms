#!/usr/bin/env php
<?php

/**

title=测试 editorModel::extendControl();
timeout=0
cid=16230

- 测试isExtends为'yes'生成继承类扩展代码
 - 属性hasMyClass @1
 - 属性hasImportControl @1
- 测试isExtends为'no'生成直接控制器类代码
 - 属性hasDirectClass @1
 - 属性hasMethodCode @1
- 测试isExtends为空字符串的边界情况属性hasPhpTag @1
- 测试无效文件路径的异常处理属性hasError @1
- 测试方法不存在的情况属性hasError @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/editor.unittest.class.php';

su('admin');

$editor = new editorTest();

$modulePath = $editor->objectModel->app->getModulePath('', 'todo');
$validFilePath = $modulePath . 'control.php' . DS . 'create';
$invalidFilePath = '/nonexistent/path/control.php' . DS . 'create';
$nonExistentMethodPath = $modulePath . 'control.php' . DS . 'nonexistentmethod';

r($editor->extendControlTest($validFilePath, 'yes')) && p('hasMyClass,hasImportControl') && e('1,1');             // 测试isExtends为'yes'生成继承类扩展代码
r($editor->extendControlTest($validFilePath, 'no')) && p('hasDirectClass,hasMethodCode') && e('1,1');            // 测试isExtends为'no'生成直接控制器类代码
r($editor->extendControlTest($validFilePath, '')) && p('hasPhpTag') && e('1');                                   // 测试isExtends为空字符串的边界情况
r($editor->extendControlTest($invalidFilePath, 'yes')) && p('hasError') && e('1');                               // 测试无效文件路径的异常处理
r($editor->extendControlTest($nonExistentMethodPath, 'no')) && p('hasError') && e('1');                       // 测试方法不存在的情况