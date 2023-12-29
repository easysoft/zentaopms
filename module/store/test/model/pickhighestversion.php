#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 storeModel->pickHighestVersion().
cid=1

- 不指定ID获取最新版本属性version @2023.12.2801
- 指定ID获取最新版本属性version @2023.12.2801

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/store.class.php';

zdTable('user')->gen(5);
su('admin');

$appIdList = array(0, 29);

$store = new storeTest();
r($store->pickHighestVersionTest('2023.11.2301', $appIdList[0])) && p('version') && e('2023.12.2801'); //不指定ID获取最新版本
r($store->pickHighestVersionTest('2023.11.2301', $appIdList[1])) && p('version') && e('2023.12.2801'); //指定ID获取最新版本
