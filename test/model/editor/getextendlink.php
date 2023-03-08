#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/editor.class.php';
su('admin');

/**

title=测试 editorModel::getExtendLink();
cid=1
pid=1

获取todo模块的扩展链接 >> 1

*/

$editor = new editorTest();
r($editor->getExtendLinkTest()) && p() && e(1);    //获取todo模块的扩展链接
