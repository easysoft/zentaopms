#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getScrumRoadMapParams();
cid=1
pid=1

测试获取产品路线图区块的参数 >> 0

*/

$block = new blockTest();

r($block->getScrumRoadMapParamsTest()) && p() && e('0'); // 测试获取产品路线图区块的参数