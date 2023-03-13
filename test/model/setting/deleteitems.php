#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/setting.class.php';
su('admin');

/**

title=测试 settingModel->deleteItems();
cid=1
pid=1

删除键是version的common模块下的系统数据 >> 0
删除键是hourPoint的common模块下的系统数据 >> 0
删除键是mode的common模块下的系统数据 >> 0
删除键是CRProduct的common模块下的系统数据 >> 0
删除键是sn的common模块下的数据 >> 0
删除键是CRExection的common模块下的数据 >> 0
删除键是mode、附件条件是safe的数据 >> 0
删除键是URSR的数据 >> 0
删除模块是story的数据 >> 0
删除附加条件是xuanxuan的数据 >> 0
删除所有者是admin的数据 >> 0
删除视图是rnd的数据 >> 0
删除所有的数据 >> 0

*/

$setting = new settingTest();

$params     = array();
$params[0]  = "vision=rnd&owner=system&module=common&section=global&key=version";
$params[1]  = "vision=rnd&owner=system&module=common&key=hourPoint";
$params[2]  = "owner=system&module=common&section=global&key=mode";
$params[3]  = "owner=system&module=common&key=CRProduct";
$params[4]  = "module=common&section=global&key=sn";
$params[5]  = "module=common&key=CRExection";
$params[6]  = "section=safe&key=mode";
$params[7]  = "key=URSR";
$params[8]  = "module=story";
$params[9]  = "section=xuanxuan";
$params[10] = "owner=admin";
$params[11] = "vision=rnd";
$params[12] = "";

r($setting->deleteItemsTest($params[0]))  && p() && e('0'); // 删除键是version的common模块下的系统数据
r($setting->deleteItemsTest($params[1]))  && p() && e('0'); // 删除键是hourPoint的common模块下的系统数据
r($setting->deleteItemsTest($params[2]))  && p() && e('0'); // 删除键是mode的common模块下的系统数据
r($setting->deleteItemsTest($params[3]))  && p() && e('0'); // 删除键是CRProduct的common模块下的系统数据
r($setting->deleteItemsTest($params[4]))  && p() && e('0'); // 删除键是sn的common模块下的数据
r($setting->deleteItemsTest($params[5]))  && p() && e('0'); // 删除键是CRExection的common模块下的数据
r($setting->deleteItemsTest($params[6]))  && p() && e('0'); // 删除键是mode、附件条件是safe的数据
r($setting->deleteItemsTest($params[7]))  && p() && e('0'); // 删除键是URSR的数据
r($setting->deleteItemsTest($params[8]))  && p() && e('0'); // 删除模块是story的数据
r($setting->deleteItemsTest($params[9]))  && p() && e('0'); // 删除附加条件是xuanxuan的数据
r($setting->deleteItemsTest($params[10])) && p() && e('0'); // 删除所有者是admin的数据
r($setting->deleteItemsTest($params[11])) && p() && e('0'); // 删除视图是rnd的数据
r($setting->deleteItemsTest($params[12])) && p() && e('0'); // 删除所有的数据

