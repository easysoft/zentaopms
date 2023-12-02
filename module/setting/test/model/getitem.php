#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/setting.class.php';
su('admin');

$config = zdTable('config');
$config->vision->range('``,rnd,lite');
$config->gen(20);

/**

title=测试 settingModel->getItem();
timeout=0
cid=1

*/

global $tester;
$settingModel = $tester->loadModel('setting');

$items     = array();
$items[0]  = "";
$items[1]  = "key=key2";
$items[2]  = "section=section3&key=key3";
$items[3]  = "module=story&section=section4&key=key4";
$items[4]  = "owner=system&module=task&section=section5&key=key5";
$items[5]  = "vision=lite&owner=user&module=bug&section=section6&key=key6";

r($settingModel->getItem($items[0]))  && p() && e('value1'); // 查询所有数据的第一条
r($settingModel->getItem($items[1]))  && p() && e('value2'); // 查询只有key条件
r($settingModel->getItem($items[2]))  && p() && e('value3'); // 查询有key和section条件
r($settingModel->getItem($items[3]))  && p() && e('value4'); // 查询有key、section和module条件
r($settingModel->getItem($items[4]))  && p() && e('value5'); // 查询有key、section、module和owner条件
r($settingModel->getItem($items[5]))  && p() && e('value6'); // 查询有key、section、module、owner和vision条件
