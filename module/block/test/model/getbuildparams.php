#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/block.class.php';
su('admin');

/**

title=测试 blockModel->getBuildParams();
cid=1
pid=1

测试获取版本区块参数 >> 数量,20,input

*/

$block = new blockTest();
$data = $block->getBuildParamsTest();

r($data) && p('count:name,default,control') && e('数量,20,input'); //测试获取版本区块参数