#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/block.class.php';
su('admin');

/**

title=测试 blockModel->getReleaseParams();
cid=1
pid=1

测试name >> 数量,20,input

*/

$block = new blockTest();

r($block->getReleaseParamsTest()) && p('name,default,control') && e('数量,20,input');  //测试name