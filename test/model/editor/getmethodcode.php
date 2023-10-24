#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/editor.class.php';
su('admin');

/**

title=测试 editorModel::getMethodCode();
cid=1
pid=1

获取todo模块的create方法的参数 >> 1

*/

$editor = new editorTest();
r($editor->getMethodCodeTest()) && p() && e(1);    //获取todo模块的create方法的参数
