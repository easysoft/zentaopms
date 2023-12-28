#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/setting.class.php';
su('admin');

$config = zdTable('config');
$config->vision->range('``,rnd,lite');
$config->gen(10);

/**

title=测试 settingModel->deleteItems();
timeout=0
cid=1
pid=1

*/

$setting = new settingTest();

$params     = array();
$params[0]  = "key=sn";
$params[1]  = "section=global&key=version";
$params[2]  = "module=common&section=global&key=mode";
$params[3]  = "owner=system&module=common&section=global&key=URSR";
$params[4]  = "vision=lite&owner=system&module=common&section=global&key=CRExecution";
$params[5]  = "owner=system";

r($setting->deleteItemsTest($params[0]))  && p() && e('9');
r($setting->deleteItemsTest($params[1]))  && p() && e('8');
r($setting->deleteItemsTest($params[2]))  && p() && e('7');
r($setting->deleteItemsTest($params[3]))  && p() && e('6');
r($setting->deleteItemsTest($params[4]))  && p() && e('5');
r($setting->deleteItemsTest($params[5]))  && p() && e('0');
