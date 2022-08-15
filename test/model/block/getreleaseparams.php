#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getReleaseParams();
cid=1
pid=1

测试name >> 数量,20,input

*/

$block = new blockTest();

r($block->getReleaseParamsTest()) && p('name,default,control') && e('数量,20,input');  //测试name