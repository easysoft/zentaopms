#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getTesttaskParams();
cid=1
pid=1

获取testtask参数 >> {"count":{"name":"数量","default":20,"control":"input"},"type":{"name":"类型","options":{"wait":"待测版本","doing":"测试中版本","blocked":"阻塞版本","done":"已测版本","all":"全部"},"control":"select"}}

*/

$block = new blockTest();

r($block->getTesttaskParamsTest()) && p() && e('{"count":{"name":"数量","default":20,"control":"input"},"type":{"name":"类型","options":{"wait":"待测版本","doing":"测试中版本","blocked":"阻塞版本","done":"已测版本","all":"全部"},"control":"select"}}'); // 获取testtask参数