#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/setting.class.php';
su('admin');

/**

title=测试 settingModel->createDAO();
cid=1
pid=1

测试查询参数包含vision、owner、module、section、key的config数据 >> rnd,system,common,global,version
测试查询参数包含vision、owner、module、key的config数据 >> 0
测试查询参数包含owner、module、section、key的config数据 >> rnd,system,common,global,mode
测试查询参数包含owner、module、key的config数据 >> rnd,system,common,,CRProduct
测试查询参数包含module、section、key的config数据 >> rnd,system,common,global,sn
测试查询参数包含module、key的config数据 >> 0
测试查询参数包含section、key的config数据 >> rnd,system,common,safe,mode
测试查询参数包含key的config数据 >> rnd,system,custom,,URSR
测试查询参数包含module的config数据 >> rnd,system,story,,reviewRules
测试查询参数包含section的config数据 >> rnd,system,common,xuanxuan,chatPort
测试查询参数包含owner的config数据 >> rnd,admin,my,common,blockInited
测试查询参数包含vision的config数据 >> rnd,admin,my,common,blockInited
测试查询参数为空的config数据 >> rnd,admin,my,common,blockInited
测试查询参数包含vision、owner、module、section、key的config数据 >> rnd,system,common,global,version
测试删除参数包含vision、owner、module、key的config数据 >> 0
测试删除参数包含owner、module、section、key的config数据 >> 1
测试删除参数包含owner、module、key的config数据 >> 1
测试删除参数包含module、section、key的config数据 >> 1
测试删除参数包含module、key的config数据 >> 0
测试删除参数包含section、key的config数据 >> 1
测试删除参数包含key的config数据 >> 2
测试删除参数包含module的config数据 >> 1
测试删除参数包含section的config数据 >> 7
测试删除参数包含owner的config数据 >> 7
测试删除参数包含vision的config数据 >> 13
测试删除参数为空的config数据 >> 0

*/

$paramString     = array();
$paramString[0]  = "vision=rnd&owner=system&module=common&section=global&key=version";
$paramString[1]  = "vision=rnd&owner=system&module=common&key=hourPoint";
$paramString[2]  = "owner=system&module=common&section=global&key=mode";
$paramString[3]  = "owner=system&module=common&key=CRProduct";
$paramString[4]  = "module=common&section=global&key=sn";
$paramString[5]  = "module=common&key=CRExection";
$paramString[6]  = "section=safe&key=mode";
$paramString[7]  = "key=URSR";
$paramString[8]  = "module=story";
$paramString[9]  = "section=xuanxuan";
$paramString[10] = "owner=admin";
$paramString[11] = "vision=rnd";
$paramString[12] = "";

$method = array('select', 'delete');
$print  = 'vision,owner,module,section,key';

$setting = new settingTest();

r($setting->createDAOTest($paramString[0], $method[0]))  && p($print) && e('rnd,system,common,global,version');    //测试查询参数包含vision、owner、module、section、key的config数据
r($setting->createDAOTest($paramString[1], $method[0]))  && p()       && e('0');                                   //测试查询参数包含vision、owner、module、key的config数据
r($setting->createDAOTest($paramString[2], $method[0]))  && p($print) && e('rnd,system,common,global,mode');       //测试查询参数包含owner、module、section、key的config数据
r($setting->createDAOTest($paramString[3], $method[0]))  && p($print) && e('rnd,system,common,,CRProduct');        //测试查询参数包含owner、module、key的config数据
r($setting->createDAOTest($paramString[4], $method[0]))  && p($print) && e('rnd,system,common,global,sn');         //测试查询参数包含module、section、key的config数据
r($setting->createDAOTest($paramString[5], $method[0]))  && p()       && e('0');                                   //测试查询参数包含module、key的config数据
r($setting->createDAOTest($paramString[6], $method[0]))  && p($print) && e('rnd,system,common,safe,mode');         //测试查询参数包含section、key的config数据
r($setting->createDAOTest($paramString[7], $method[0]))  && p($print) && e('rnd,system,custom,,URSR');             //测试查询参数包含key的config数据
r($setting->createDAOTest($paramString[8], $method[0]))  && p($print) && e('rnd,system,story,,reviewRules');       //测试查询参数包含module的config数据
r($setting->createDAOTest($paramString[9], $method[0]))  && p($print) && e('rnd,system,common,xuanxuan,chatPort'); //测试查询参数包含section的config数据
r($setting->createDAOTest($paramString[10], $method[0])) && p($print) && e('rnd,admin,my,common,blockInited');     //测试查询参数包含owner的config数据
r($setting->createDAOTest($paramString[11], $method[0])) && p($print) && e('rnd,admin,my,common,blockInited');     //测试查询参数包含vision的config数据
r($setting->createDAOTest($paramString[12], $method[0])) && p($print) && e('rnd,admin,my,common,blockInited');     //测试查询参数为空的config数据
r($setting->createDAOTest($paramString[0], $method[0]))  && p($print) && e('rnd,system,common,global,version');    //测试查询参数包含vision、owner、module、section、key的config数据
r($setting->createDAOTest($paramString[1], $method[1]))  && p()       && e('0');                                   //测试删除参数包含vision、owner、module、key的config数据
r($setting->createDAOTest($paramString[2], $method[1]))  && p()       && e('1');                                   //测试删除参数包含owner、module、section、key的config数据
r($setting->createDAOTest($paramString[3], $method[1]))  && p()       && e('1');                                   //测试删除参数包含owner、module、key的config数据
r($setting->createDAOTest($paramString[4], $method[1]))  && p()       && e('1');                                   //测试删除参数包含module、section、key的config数据
r($setting->createDAOTest($paramString[5], $method[1]))  && p()       && e('0');                                   //测试删除参数包含module、key的config数据
r($setting->createDAOTest($paramString[6], $method[1]))  && p()       && e('1');                                   //测试删除参数包含section、key的config数据
r($setting->createDAOTest($paramString[7], $method[1]))  && p()       && e('2');                                   //测试删除参数包含key的config数据
r($setting->createDAOTest($paramString[8], $method[1]))  && p()       && e('1');                                   //测试删除参数包含module的config数据
r($setting->createDAOTest($paramString[9], $method[1]))  && p()       && e('7');                                   //测试删除参数包含section的config数据
r($setting->createDAOTest($paramString[10], $method[1])) && p()       && e('7');                                   //测试删除参数包含owner的config数据
r($setting->createDAOTest($paramString[11], $method[1])) && p()       && e('13');                                  //测试删除参数包含vision的config数据
r($setting->createDAOTest($paramString[12], $method[1])) && p()       && e('0');                                   //测试删除参数为空的config数据

