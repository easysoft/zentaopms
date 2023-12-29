#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 storeModel->getAppInfo().
cid=1

- 测试ID为0 @0
- 测试使用查询不到数据的ID @0
- 测试使用查询不到数据的ID属性id @70
- 测试传参analysis为true属性id @70
- 测试传参name为禅道属性id @70
- 测试传参version为1属性id @70
- 测试传参channel为stable属性id @70

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/store.class.php';

zdTable('user')->gen(5);
su('admin');

$appIdList = array(0, 1, 70);
$analysis  = array(false, true);
$name      = array('', '禅道');
$version   = array('', '1');
$channel   = array('', 'stable');

$store = new storeTest();
r($store->getAppInfoTest($appIdList[0], $analysis[0], $name[0], $version[0], $channel[0])) && p()     && e('0'); //测试ID为0
r($store->getAppInfoTest($appIdList[1], $analysis[0], $name[0], $version[0], $channel[0])) && p()     && e('0'); //测试使用查询不到数据的ID
r($store->getAppInfoTest($appIdList[2], $analysis[0], $name[0], $version[0], $channel[0])) && p('id') && e('70'); //测试使用查询不到数据的ID

r($store->getAppInfoTest($appIdList[2], $analysis[1], $name[0], $version[0], $channel[0])) && p('id') && e('70'); //测试传参analysis为true
r($store->getAppInfoTest($appIdList[2], $analysis[0], $name[1], $version[0], $channel[0])) && p('id') && e('70'); //测试传参name为禅道
r($store->getAppInfoTest($appIdList[2], $analysis[0], $name[0], $version[1], $channel[0])) && p('id') && e('70'); //测试传参version为1
r($store->getAppInfoTest($appIdList[2], $analysis[0], $name[0], $version[0], $channel[1])) && p('id') && e('70'); //测试传参channel为stable

