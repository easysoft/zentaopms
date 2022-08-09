#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getScrumProductParams();
cid=1
pid=1

测试获取产品区块的参数 >> 数量;20;input

*/

$block = new blockTest();

r($block->getScrumProductParamsTest()) && p('count:name;count:default;count:control') && e('数量;20;input'); // 测试获取产品区块的参数