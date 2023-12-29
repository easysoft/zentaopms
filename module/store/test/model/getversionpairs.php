#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 storeModel->getVersionPairs().
cid=1

- 测试ID为0 @0
- 测试获取禅道开源版的版本属性2023.12.2801 @18.10-2023.12.2801

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/store.class.php';

zdTable('user')->gen(5);
su('admin');

$appIdList = array(0, 29);

$store = new storeTest();
r($store->getVersionPairsTest($appIdList[0])) && p()               && e('0');                  //测试ID为0
r($store->getVersionPairsTest($appIdList[1])) && p('2023.12.2801') && e('18.10-2023.12.2801'); //测试获取禅道开源版的版本
