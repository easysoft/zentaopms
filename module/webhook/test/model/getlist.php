#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/webhook.class.php';
su('admin');

/**

title=测试 webhookModel->getList();
cid=1
pid=1

统计获取list里元素数量 >> 15
取出其中一个id >> 1

*/

$webhook = new webhookTest();

$orderBy = array();
$orderBy[0] = 'id_desc';
$orderBy[1] = '';

$pager = array();
$pager[0] = null;

$decode = array();
$decode[0] = true;
$decode[1] = '';

$result1 = $webhook->getListTest($orderBy[0]);

r(count($result1)) && p()       && e('15'); //统计获取list里元素数量
r($result1)        && p('1:id') && e('1');  //取出其中一个id