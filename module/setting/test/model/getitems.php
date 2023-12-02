#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/setting.class.php';
su('admin');

$config = zdTable('config');
$config->vision->range('``,rnd,lite');
$config->gen(20);

/**

title=测试 settingModel->getItems();
timeout=0
cid=1

*/

global $tester;
$settingModel = $tester->loadModel('setting');

$items     = array();
$items[0]  = "";
$items[1]  = "key=key2";
$items[2]  = "section=section3";
$items[3]  = "module=story";
$items[4]  = "owner=system";
$items[5]  = "vision=lite";

r(count($settingModel->getItems($items[0])))  && p()          && e('20');     // 查询所有数据的记录数
r($settingModel->getItems($items[1]))         && p('2:value') && e('value2'); // 查询key条件的所有数据
r($settingModel->getItems($items[2]))         && p('3:value') && e('value3'); // 查询section条件的所有数据
r(count($settingModel->getItems($items[3])))  && p()          && e('7');      // 查询module条件的记录数
r(count($settingModel->getItems($items[4])))  && p()          && e('10');     // 查询owner条件的记录数
r(count($settingModel->getItems($items[5])))  && p()          && e('6');      // 查询vision条件的记录数
