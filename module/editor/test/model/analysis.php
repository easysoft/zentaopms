#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/editor.unittest.class.php';
su('admin');

/**

title=测试 editorModel::analysis();
cid=1
pid=1

获取todo模块的文件列表 >> 1

*/

$editor = new editorTest();
r($editor->analysisTest()) && p() && e(1);    //获取todo模块的文件列表
