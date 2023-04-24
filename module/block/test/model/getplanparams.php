#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/block.class.php';
su('admin');

/**

title=测试 blockModel->getPlanParams();
cid=1
pid=1

获取计划参数 >> {"count":{"name":"\u6570\u91cf","default":20,"control":"input"}}

*/

$block = new blockTest();

r($block->getPlanParamsTest()) && p() && e('{"count":{"name":"\u6570\u91cf","default":20,"control":"input"}}'); // 获取计划参数