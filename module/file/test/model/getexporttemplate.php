#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/file.class.php';

/**

title=测试 fileModel->getExportTemplate();
cid=1
pid=1

测试获取 用户admin task 模块的用户模板 >> 1
测试获取 用户admin bug 模块的用户模板 >> 0
测试获取 用户admin story 模块的用户模板 >> 2
测试获取 用户dev10 task 模块的用户模板 >> 0
测试获取 用户dev10 bug 模块的用户模板 >> 1
测试获取 用户dev10 story 模块的用户模板 >> 2
测试获取 用户test10 task 模块的用户模板 >> 1
测试获取 用户test10 bug 模块的用户模板 >> 0
测试获取 用户test10 story 模块的用户模板 >> 2

*/
$moduleName = array('task', 'bug', 'story');

$file = new fileTest();

su('admin');
r($file->getExportTemplateTest($moduleName[0])) && p() && e('1'); // 测试获取 用户admin task 模块的用户模板
r($file->getExportTemplateTest($moduleName[1])) && p() && e('0'); // 测试获取 用户admin bug 模块的用户模板
r($file->getExportTemplateTest($moduleName[2])) && p() && e('2'); // 测试获取 用户admin story 模块的用户模板
su('dev10');
r($file->getExportTemplateTest($moduleName[0])) && p() && e('0'); // 测试获取 用户dev10 task 模块的用户模板
r($file->getExportTemplateTest($moduleName[1])) && p() && e('1'); // 测试获取 用户dev10 bug 模块的用户模板
r($file->getExportTemplateTest($moduleName[2])) && p() && e('2'); // 测试获取 用户dev10 story 模块的用户模板
su('test10');
r($file->getExportTemplateTest($moduleName[0])) && p() && e('1'); // 测试获取 用户test10 task 模块的用户模板
r($file->getExportTemplateTest($moduleName[1])) && p() && e('0'); // 测试获取 用户test10 bug 模块的用户模板
r($file->getExportTemplateTest($moduleName[2])) && p() && e('2'); // 测试获取 用户test10 story 模块的用户模板