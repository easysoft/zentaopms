#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 storeModel->getAppSettings().
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/store.class.php';

zdTable('user')->gen(5);
su('admin');

$appIdList = array(0, 29);

$store = new storeTest();
r($store->getAppSettingsTest($appIdList[0])) && p() && e('0'); //测试ID为0
r($store->getAppSettingsTest($appIdList[1])) && p() && e('0'); //测试ID为29【服务器错误，需要排查原因】
