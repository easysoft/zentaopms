#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/block.class.php';
su('admin');

/**

title=测试 blockModel->getTodoParams();
cid=1
pid=1

获取todo参数 >> {"count":{"name":"数量","default":20,"control":"input"}}

*/

$block = new blockTest();

r($block->getTodoParamsTest()) && p() && e('{"count":{"name":"数量","default":20,"control":"input"}}'); // 获取todo参数