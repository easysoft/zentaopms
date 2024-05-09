#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/editor.unittest.class.php';
su('admin');

/**

title=测试 editorModel::addLink4Dir();
cid=1
pid=1

添加todo模块的链接 >> 1,1,1,1,1,1,1,1

*/

$editor = new editorTest();
r($editor->addLink4DirTest()) && p() && e('1,1,1,1,1,1,1,1');    //添加todo模块的链接
