#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/setting.class.php';
su('admin');

zdTable('config')->gen(7);

/**

title=测试 settingModel->getItems();
timeout=0
cid=1

- 查询所有数据中的第一条数据第1条的value属性 @0
- 查询所有者是系统的第一条数据第1条的value属性 @0
- 查询所有者是系统的第一条数据第1条的value属性 @0
- 查询所属模块是custom的第一条数据第1条的value属性 @0
- 查询附加条件是global的第一条数据第7条的value属性 @f205720305272543052e3d689afdb5b8
- 查询值是CRProduct的第一条数据第2条的value属性 @1
- 查询研发界面中所有者是系统的第一条数据第3条的value属性 @1
- 查询研发界面中所属模块是custom的第一条数据第1条的value属性 @0
- 查询研发界面中附加条件是global的第一条数据第7条的value属性 @f205720305272543052e3d689afdb5b8
- 查询研发界面中值是CRProduct的第一条数据第2条的value属性 @1
- 查询所有者是系统并且所属模块是custom的第一条数据第1条的value属性 @0
- 查询所有者是系统并且附加条件是global的第一条数据第3条的value属性 @1
- 查询所有者是系统并且值是CRProduct的第一条数据第2条的value属性 @1
- 查询所有者是系统并且所属模块是common、附加条件是global的第一条数据第6条的value属性 @10.0
- 查询所有者是系统并且所属模块是common、值是CRProduct的第一条数据第3条的value属性 @1
- 查询所有者是系统并且所属模块是common、附加条件是global、值是version的第一条数据第6条的value属性 @10.0
- 查询所有者是admin并且所属模块是common、值是URSR的第一条数据第5条的value属性 @0
- 查询所有者是admin并且所属模块是my、附加条件是block、值是initVersion的第一条数据第4条的value属性 @0
- 查询不存在的界面配置 @0
- 查询不存在的所有者配置 @0
- 查询不存在的模块配置 @0
- 查询不存在的附加条件配置 @0
- 查询不存在的值配置 @0

*/

$setting = new settingTest();

$items     = array();
$items[0]  = "";
$items[1]  = "vision=rnd";
$items[2]  = "owner=system";
$items[3]  = "module=custom";
$items[4]  = "section=global";
$items[5]  = "key=CRProduct";
$items[6]  = "vision=rnd&owner=system";
$items[7]  = "vision=rnd&module=custom";
$items[8]  = "vision=rnd&section=global";
$items[9]  = "vision=rnd&key=CRProduct";
$items[10] = "owner=system&module=custom";
$items[11] = "owner=system&section=global";
$items[12] = "owner=system&key=CRProduct";
$items[13] = "owner=system&module=common&section=global";
$items[14] = "owner=system&module=common&key=CRExecution";
$items[15] = "owner=system&module=common&section=global&key=version";
$items[16] = "owner=admin&module=common&key=URSR";
$items[17] = "owner=admin&module=my&section=block&key=initVersion";
$items[18] = "vision=test";
$items[19] = "owner=test";
$items[20] = "module=test";
$items[21] = "section=test";
$items[22] = "key=test";

r($setting->getItemsTest($items[0]))  && p('1:value') && e('0'); // 查询所有数据中的第一条数据
r($setting->getItemsTest($items[1]))  && p('1:value') && e('0'); // 查询所有者是系统的第一条数据
r($setting->getItemsTest($items[2]))  && p('1:value') && e('0'); // 查询所有者是系统的第一条数据
r($setting->getItemsTest($items[3]))  && p('1:value') && e('0'); // 查询所属模块是custom的第一条数据
r($setting->getItemsTest($items[4]))  && p('7:value') && e('f205720305272543052e3d689afdb5b8'); // 查询附加条件是global的第一条数据
r($setting->getItemsTest($items[5]))  && p('2:value') && e('1'); // 查询值是CRProduct的第一条数据
r($setting->getItemsTest($items[6]))  && p('3:value') && e('1'); // 查询研发界面中所有者是系统的第一条数据
r($setting->getItemsTest($items[7]))  && p('1:value') && e('0'); // 查询研发界面中所属模块是custom的第一条数据
r($setting->getItemsTest($items[8]))  && p('7:value') && e('f205720305272543052e3d689afdb5b8'); // 查询研发界面中附加条件是global的第一条数据
r($setting->getItemsTest($items[9]))  && p('2:value') && e('1'); // 查询研发界面中值是CRProduct的第一条数据
r($setting->getItemsTest($items[10])) && p('1:value') && e('0'); // 查询所有者是系统并且所属模块是custom的第一条数据
r($setting->getItemsTest($items[11])) && p('3:value') && e('1'); // 查询所有者是系统并且附加条件是global的第一条数据
r($setting->getItemsTest($items[12])) && p('2:value') && e('1'); // 查询所有者是系统并且值是CRProduct的第一条数据
r($setting->getItemsTest($items[13])) && p('6:value') && e('10.0'); // 查询所有者是系统并且所属模块是common、附加条件是global的第一条数据
r($setting->getItemsTest($items[14])) && p('3:value') && e('1'); // 查询所有者是系统并且所属模块是common、值是CRProduct的第一条数据
r($setting->getItemsTest($items[15])) && p('6:value') && e('10.0'); // 查询所有者是系统并且所属模块是common、附加条件是global、值是version的第一条数据
r($setting->getItemsTest($items[16])) && p('5:value') && e('0'); // 查询所有者是admin并且所属模块是common、值是URSR的第一条数据
r($setting->getItemsTest($items[17])) && p('4:value') && e('0'); // 查询所有者是admin并且所属模块是my、附加条件是block、值是initVersion的第一条数据
r($setting->getItemsTest($items[18])) && p() && e('0'); // 查询不存在的界面配置
r($setting->getItemsTest($items[19])) && p() && e('0'); // 查询不存在的所有者配置
r($setting->getItemsTest($items[20])) && p() && e('0'); // 查询不存在的模块配置
r($setting->getItemsTest($items[21])) && p() && e('0'); // 查询不存在的附加条件配置
r($setting->getItemsTest($items[22])) && p() && e('0'); // 查询不存在的值配置