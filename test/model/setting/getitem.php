#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/setting.class.php';
su('admin');

/**

title=测试 settingModel->getItem();
cid=1
pid=1

查询所有数据的第一条 >> 1
查询所有者是系统的第一条数据 >> 1
查询所有者是系统的第一条数据 >> 1
查询所属模块是custom的第一条数据 >> 1
查询附加条件是global的第一条数据 >> 1
查询值是CRProduct的第一条数据 >> 1
查询研发界面中所有者是系统的第一条数据 >> 1
查询研发界面中所属模块是custom的第一条数据 >> 1
查询研发界面中附加条件是global的第一条数据 >> 1
查询研发界面中值是CRProduct的第一条数据 >> 1
查询所有者是系统并且所属模块是custom的第一条数据 >> 1
查询所有者是系统并且附加条件是global的第一条数据 >> 1
查询所有者是系统并且值是CRProduct的第一条数据 >> 1
查询所有者是系统并且所属模块是common、附加条件是global的第一条数据 >> 1
查询所有者是系统并且所属模块是common、值是CRProduct的第一条数据 >> 1
查询所有者是系统并且所属模块是common、附加条件是global、值是version的第一条数据 >> 1
查询所有者是admin并且所属模块是common、值是URSR的第一条数据 >> 1
查询所有者是admin并且所属模块是my、附加条件是block、值是initVersion的第一条数据 >> 1
查询不存在的界面配置 >> 0
查询不存在的所有者配置 >> 0
查询不存在的模块配置 >> 0
查询不存在的附加条件配置 >> 0
查询不存在的值配置 >> 0

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

r($setting->getItemTest($items[0]))  && p() && e('1'); // 查询所有数据的第一条
r($setting->getItemTest($items[1]))  && p() && e('1'); // 查询所有者是系统的第一条数据
r($setting->getItemTest($items[2]))  && p() && e('1'); // 查询所有者是系统的第一条数据
r($setting->getItemTest($items[3]))  && p() && e('1'); // 查询所属模块是custom的第一条数据
r($setting->getItemTest($items[4]))  && p() && e('1'); // 查询附加条件是global的第一条数据
r($setting->getItemTest($items[5]))  && p() && e('1'); // 查询值是CRProduct的第一条数据
r($setting->getItemTest($items[6]))  && p() && e('1'); // 查询研发界面中所有者是系统的第一条数据
r($setting->getItemTest($items[7]))  && p() && e('1'); // 查询研发界面中所属模块是custom的第一条数据
r($setting->getItemTest($items[8]))  && p() && e('1'); // 查询研发界面中附加条件是global的第一条数据
r($setting->getItemTest($items[9]))  && p() && e('1'); // 查询研发界面中值是CRProduct的第一条数据
r($setting->getItemTest($items[10])) && p() && e('1'); // 查询所有者是系统并且所属模块是custom的第一条数据
r($setting->getItemTest($items[11])) && p() && e('1'); // 查询所有者是系统并且附加条件是global的第一条数据
r($setting->getItemTest($items[12])) && p() && e('1'); // 查询所有者是系统并且值是CRProduct的第一条数据
r($setting->getItemTest($items[13])) && p() && e('1'); // 查询所有者是系统并且所属模块是common、附加条件是global的第一条数据
r($setting->getItemTest($items[14])) && p() && e('1'); // 查询所有者是系统并且所属模块是common、值是CRProduct的第一条数据
r($setting->getItemTest($items[15])) && p() && e('1'); // 查询所有者是系统并且所属模块是common、附加条件是global、值是version的第一条数据
r($setting->getItemTest($items[16])) && p() && e('1'); // 查询所有者是admin并且所属模块是common、值是URSR的第一条数据
r($setting->getItemTest($items[17])) && p() && e('1'); // 查询所有者是admin并且所属模块是my、附加条件是block、值是initVersion的第一条数据
r($setting->getItemTest($items[18])) && p() && e('0'); // 查询不存在的界面配置
r($setting->getItemTest($items[19])) && p() && e('0'); // 查询不存在的所有者配置
r($setting->getItemTest($items[20])) && p() && e('0'); // 查询不存在的模块配置
r($setting->getItemTest($items[21])) && p() && e('0'); // 查询不存在的附加条件配置
r($setting->getItemTest($items[22])) && p() && e('0'); // 查询不存在的值配置