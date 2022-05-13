#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getProjectStatisticParams();
cid=1
pid=1

获取project静态param >> {"count":{"name":"数量","default":20,"control":"input"},"type":{"name":"类型","options":{"undone":"未完成","doing":"进行中","all":"全部","involved":"我参与的"},"control":"select"}}

*/

$block = new blockTest();

r($block->getProjectStatisticParamsTest()) && p() && e('{"count":{"name":"数量","default":20,"control":"input"},"type":{"name":"类型","options":{"undone":"未完成","doing":"进行中","all":"全部","involved":"我参与的"},"control":"select"}}'); // 获取project静态param