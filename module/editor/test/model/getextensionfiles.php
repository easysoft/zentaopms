#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/editor.unittest.class.php';
su('admin');

/**

title=测试 editorModel::getExtensionFiles();
cid=1
pid=1

获取misc模块的扩展文件列表 >> 1

*/

$editor = new editorTest();
r($editor->getExtensionFilesTest('misc')) && p() && e(1);    //获取misc模块的扩展文件列表
