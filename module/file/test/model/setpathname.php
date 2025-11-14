#!/usr/bin/env php
<?php

/**

title=测试 fileModel::setPathName();
timeout=0
cid=16534

- 测试正常的文件ID和扩展名属性reg @1
- 测试边界值文件ID为0和空扩展名属性reg @1
- 测试大数值文件ID和常见扩展名属性reg @1
- 测试特殊字符扩展名属性reg @1
- 测试字符串类型文件ID属性reg @1
- 测试生成路径名包含时间戳格式属性timeFormat @1
- 测试生成路径名的唯一性属性uniqueness @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/file.unittest.class.php';
su('admin');

$file = new fileTest();

r($file->setPathNameTest(1, 'txt'))       && p('reg') && e('1'); // 测试正常的文件ID和扩展名
r($file->setPathNameTest(0, ''))          && p('reg') && e('1'); // 测试边界值文件ID为0和空扩展名
r($file->setPathNameTest(999999, 'pdf'))  && p('reg') && e('1'); // 测试大数值文件ID和常见扩展名
r($file->setPathNameTest(123, 'jpg-test')) && p('reg') && e('1'); // 测试特殊字符扩展名
r($file->setPathNameTest('456', 'png'))   && p('reg') && e('1'); // 测试字符串类型文件ID
r($file->setPathNameTest(789, 'docx'))    && p('timeFormat') && e('1'); // 测试生成路径名包含时间戳格式
r($file->setPathNameTest(100, 'txt'))     && p('uniqueness') && e('1'); // 测试生成路径名的唯一性