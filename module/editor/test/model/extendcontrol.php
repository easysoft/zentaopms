#!/usr/bin/env php
<?php

/**

title=测试 editorModel::extendControl();
timeout=0
cid=0

- 测试isExtends为'yes'生成继承类扩展代码 >> 期望包含helper::importControl和class mytodo extends todo
- 测试isExtends为'no'生成直接控制器类代码 >> 期望包含class todo extends control和方法代码
- 测试isExtends为空字符串的边界情况 >> 期望按照默认处理方式生成代码
- 测试无效文件路径的异常处理 >> 期望返回错误信息或空结果
- 测试有效文件路径但方法不存在的情况 >> 期望生成基础类结构但无具体方法代码

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
r($editor->extendControlTest($nonExistentMethodPath, 'no')) && p('hasDirectClass,hasMethodCode') && e('1,0');   // 测试方法不存在的情况