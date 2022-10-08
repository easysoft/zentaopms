#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getWaterfallRiskParams();
cid=1
pid=1

获取waterfall risk 参数 >> {"count":{"name":"数量","default":20,"control":"input"},"type":{"name":"类型","options":null,"control":"select"},"orderBy":{"name":"排序","options":{"id_asc":"ID 递增","id_desc":"ID 递减","status_asc":"状态正序","status_desc":"状态倒序"},"control":"select"}}

*/

$block = new blockTest();

r($block->getWaterfallRiskParamsTest()) && p() && e('{"count":{"name":"数量","default":20,"control":"input"},"type":{"name":"类型","options":null,"control":"select"},"orderBy":{"name":"排序","options":{"id_asc":"ID 递增","id_desc":"ID 递减","status_asc":"状态正序","status_desc":"状态倒序"},"control":"select"}}'); // 获取waterfall risk 参数