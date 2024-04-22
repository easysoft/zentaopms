#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/editor.unittest.class.php';
su('admin');

/**

title=测试 editorModel::getParam();
cid=1
pid=1

获取todo模块的create方法的参数 >> 1

*/

$editor = new editorTest();
r($editor->getParamTest()) && p() && e(1);    //获取todo模块的create方法的参数
