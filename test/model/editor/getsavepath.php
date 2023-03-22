#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/editor.class.php';
su('admin');

/**

title=测试 editorModel::getSavePath();
cid=1
pid=1

获取todo模块的扩展文件存储路径 >> 1,1,1,1,1,1,1,1,1,1,1

*/

$editor = new editorTest();
r($editor->getSavePathTest()) && p() && e('1,1,1,1,1,1,1,1,1,1,1');    //获取todo模块的扩展文件存储路径
