#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/setting.class.php';
su('admin');

$config = zdTable('config');
$config->vision->range('``,rnd,lite');
$config->gen(20);

/**

title=测试 settingModel->deleteItems();
timeout=0
cid=1
pid=1

*/

$setting = new settingTest();

$params     = array();
$params[0]  = "key=key2";
$params[1]  = "section=section3&key=key3";
$params[2]  = "module=story&section=section4&key=key4";
$params[3]  = "owner=system&module=task&section=section5&key=key5";
$params[4]  = "vision=lite&owner=user&module=bug&section=section6&key=key6";
$params[5]  = "owner=system";

r($setting->deleteItemsTest($params[0]))  && p() && e('19');
r($setting->deleteItemsTest($params[1]))  && p() && e('18');
r($setting->deleteItemsTest($params[2]))  && p() && e('17');
r($setting->deleteItemsTest($params[3]))  && p() && e('16');
r($setting->deleteItemsTest($params[4]))  && p() && e('15');
r($setting->deleteItemsTest($params[5]))  && p() && e('7');
