#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/setting.class.php';
su('admin');

$config = zdTable('config');
$config->vision->range('``,rnd,lite');
$config->gen(10);

/**

title=测试 settingModel->getItem();
timeout=0
cid=1

*/

global $tester;
$settingModel = $tester->loadModel('setting');

$items    = array();
$items[0] = "";
$items[1] = "key=sn";
$items[2] = "section=global&key=version";
$items[3] = "module=common&section=global&key=mode";
$items[4] = "owner=system&module=common&section=global&key=URSR";
$items[5] = "vision=lite&owner=system&module=common&section=global&key=CRExecution";

r($settingModel->getItem($items[0]))  && p() && e('0');     // 查询所有数据的第一条
r($settingModel->getItem($items[1]))  && p() && e('f205720305272543052e3d689afdb5b8'); // 查询只有key条件
r($settingModel->getItem($items[2]))  && p() && e('10.0'); // 查询有key和section条件
r($settingModel->getItem($items[3]))  && p() && e('ALM');  // 查询有key、section和module条件
r($settingModel->getItem($items[4]))  && p() && e('2');    // 查询有key、section、module和owner条件
r($settingModel->getItem($items[5]))  && p() && e('1');    // 查询有key、section、module、owner和vision条件
