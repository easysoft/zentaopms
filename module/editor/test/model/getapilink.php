#!/usr/bin/env php
<?php

/**

title=测试 editorModel::getAPILink();
cid=0

- 测试extendModel动作的API链接生成 >> 正常生成包含debug和extendModel的链接
- 测试extendControl动作的API链接生成 >> 正常生成包含debug和extendControl的链接
- 测试空action参数的API链接生成 >> 正常生成有效的API链接
- 测试特殊字符文件路径的API链接生成 >> 正常处理特殊字符并生成链接
- 测试链接格式包含debug参数验证 >> 生成的链接包含debug参数

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/editor.unittest.class.php';

su('admin');

$editor = new editorTest();

r($editor->getAPILinkTest()) && p('hasDebug,hasAction,hasFilePath') && e('1,1,1');                              // 测试extendModel动作的API链接生成
r($editor->getAPILinkTest('', 'extendControl')) && p('hasDebug,hasAction') && e('1,1');                         // 测试extendControl动作的API链接生成
r($editor->getAPILinkTest('', '')) && p('hasDebug') && e('1');                                                   // 测试空action参数的API链接生成
r($editor->getAPILinkTest('/path/with@special#chars/model.php/method', 'extendModel')) && p('hasDebug,hasAction') && e('1,1'); // 测试特殊字符文件路径的API链接生成
r($editor->getAPILinkTest()) && p('link') && c('debug');                                                         // 测试链接格式包含debug参数验证