#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 storeModel->getAppMapByNames().
cid=1

- 测试传入查询名称为空 @0
- 测试查询名称为adminer和zentao
 - 第adminer条的name属性 @adminer
 - 第zentao条的name属性 @zentao

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/store.class.php';

zdTable('user')->gen(5);
su('admin');

$nameList = array(array(), array('adminer', 'zentao'));

$store = new storeTest();
r($store->getAppMapByNamesTest($nameList[0])) && p()                           && e('0');              //测试传入查询名称为空
r($store->getAppMapByNamesTest($nameList[1])) && p('adminer:name;zentao:name') && e('adminer;zentao'); //测试查询名称为adminer和zentao
