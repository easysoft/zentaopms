#!/usr/bin/env php
<?php

/**

title=测试 editorModel::getTwoGradeFiles();
cid=16240

- 测试步骤1：正常目录情况 >> 检查返回结果是数组且包含语言目录
- 测试步骤2：空目录结构输入 >> 检查返回空数组且结构正确
- 测试步骤3：不存在目录路径 >> 检查返回空数组
- 测试步骤4：无效目录路径输入 >> 检查错误处理和返回空数组
- 测试步骤5：系统文件过滤验证 >> 检查系统文件被正确过滤

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$editorTest = new editorModelTest();
$emptyDir   = sys_get_temp_dir() . DS . 'editor.getTwoGradeFiles.empty' . DS;

if(!is_dir($emptyDir)) mkdir($emptyDir, 0777, true);

r($editorTest->getTwoGradeFilesTest()) && p('isArray,hasLangDir') && e('1,1');
r($editorTest->getTwoGradeFilesTest($emptyDir)) && p('isArray,isEmpty') && e('1,1');
r($editorTest->getTwoGradeFilesTest('/nonexistent/path')) && p('isArray,isEmpty') && e('1,1');
r($editorTest->getTwoGradeFilesTest('/invalid/path/with-special_chars')) && p('isArray,isEmpty') && e('1,1');
r($editorTest->getTwoGradeFilesTest()) && p('hasNoSystemFiles') && e('1');
