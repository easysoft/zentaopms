#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');
zenData('system')->gen(10);
zenData('product')->gen(10);

/**

title=测试 systemModel::getList();
timeout=0
cid=18736

- 查询默认排序应用第1条的name属性 @应用1
- 获取所有应用数量 @1
- 查询默认排序应用下的状态第1条的status属性 @active
- 查询ID正序排序应用第1条的name属性 @应用1
- 查询状态倒序排序应用第1条的name属性 @应用1

*/
global $tester;
$system = $tester->loadModel('system');

$orderList = array('id_desc', 'id_asc', 'status_desc', 'status_asc');

r($system->getList(1))                && p('1:name')   && e('应用1');  // 查询默认排序应用
r(count($system->getList(1)))         && p()           && e('1');      // 获取所有应用数量
r($system->getList(1))                && p('1:status') && e('active'); // 查询默认排序应用下的状态

r($system->getList(1, 'all',    $orderList[1])) && p('1:name') && e('应用1'); // 查询ID正序排序应用
r($system->getList(1, 'active', $orderList[2])) && p('1:name') && e('应用1'); // 查询状态倒序排序应用