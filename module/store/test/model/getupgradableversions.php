#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 storeModel->getUpgradableVersions().
cid=1

- 测试不传ID和name自动获取可升级版本第0条的app_version属性 @18.10
- 测试传入ID为29获取禅道开源版的可升级版本第0条的app_version属性 @18.10
- 测试传入name为zentao获取禅道开源版的可升级版本第0条的app_version属性 @18.10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/store.class.php';

zdTable('user')->gen(5);
su('admin');

$appIdList   = array(0, 29);
$appNameList = array('', 'zentao');

$store = new storeTest();
r($store->getUpgradableVersions('2023.11.2301', $appIdList[0], $appNameList[0])) && p('0:app_version') && e('18.10'); //测试不传ID和name自动获取可升级版本
r($store->getUpgradableVersions('2023.11.2301', $appIdList[1], $appNameList[0])) && p('0:app_version') && e('18.10'); //测试传入ID为29获取禅道开源版的可升级版本
r($store->getUpgradableVersions('2023.11.2301', $appIdList[0], $appNameList[1])) && p('0:app_version') && e('18.10'); //测试传入name为zentao获取禅道开源版的可升级版本
