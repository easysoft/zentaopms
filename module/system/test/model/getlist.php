#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');
zenData('system')->gen(10);

/**
*/
global $tester;
$system = $tester->loadModel('system');

$orderList = array('id_desc', 'id_asc', 'status_desc', 'status_asc');

r($system->getList())              && p('0:name')   && e('应用10'); // 查询默认排序应用
r(count($system->getList()))       && p()           && e('10');     // 获取所有应用数量
r($system->getList())              && p('0:status') && e('active'); // 查询默认排序应用下的状态
r($system->getList($orderList[1])) && p('0:name')   && e('应用1');  // 查询ID正序排序应用
r($system->getList($orderList[2])) && p('0:name')   && e('应用1');  // 查询状态倒序排序应用
