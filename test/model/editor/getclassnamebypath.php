#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/editor.class.php';
su('admin');

/**

title=测试 editorModel::getClassNameByPath();
cid=1
pid=1

根据文件路径获取类名 >> 1,1

*/

$editor = new editorTest();
r($editor->getClassNameByPathTest()) && p() && e('1,1');    //根据文件路径获取类名
