#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 storeModel->appLatestVersion().
cid=1

- 测试传入ID为0 @0
- 测试获取禅道开源版的最新版本第2023.12.2801条的app_version属性 @18.10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/store.class.php';

zdTable('user')->gen(5);
su('admin');

$appIdList          = array(0, 29);
$currentVersionList = array('2023.11.2301');

$store = new storeTest();
r($store->appVersionListTest($appIdList[0], $currentVersionList[0])) && p()                           && e('0');     //测试传入ID为0
r($store->appVersionListTest($appIdList[1], $currentVersionList[0])) && p('2023.12.2801:app_version') && e('18.10'); //测试获取禅道开源版的目前最新版本
