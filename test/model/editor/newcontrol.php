#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/editor.class.php';
su('admin');

/**

title=测试 editorModel::newControl();
cid=1
pid=1

获取todo模块的control新扩展内容 >> 1

*/

$editor = new editorTest();
r($editor->newControlTest()) && p() && e(1);    //获取todo模块的control新扩展内容
