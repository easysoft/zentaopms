#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('config')->gen(7);

/**

title=测试 settingModel->createDAO();
timeout=0
cid=18358

- 测试查询参数包含vision、owner、module、section、key的config数据
 - 属性vision @rnd
 - 属性owner @system
 - 属性module @common
 - 属性section @global
 - 属性key @version
- 测试查询参数包含vision、owner、module、key的config数据
 - 属性vision @rnd
 - 属性owner @system
 - 属性module @common
 - 属性section @global
 - 属性key @hourPoint
- 测试查询参数包含owner、module、section、key的config数据
 - 属性vision @rnd
 - 属性owner @system
 - 属性module @common
 - 属性section @global
 - 属性key @mode
- 测试查询参数包含owner、module、key的config数据
 - 属性vision @rnd
 - 属性owner @system
 - 属性module @common
 - 属性section @global
 - 属性key @CRProduct
- 测试查询参数包含module、section、key的config数据
 - 属性vision @rnd
 - 属性owner @system
 - 属性module @common
 - 属性section @global
 - 属性key @sn
- 测试查询参数包含module、key的config数据
 - 属性vision @rnd
 - 属性owner @system
 - 属性module @common
 - 属性section @global
 - 属性key @CRExecution
- 测试查询参数包含section、key的config数据
 - 属性vision @rnd
 - 属性owner @system
 - 属性module @common
 - 属性section @global
 - 属性key @URSR
- 测试查询module=common的config数据
 - 属性vision @rnd
 - 属性owner @system
 - 属性module @common
 - 属性section @global
 - 属性key @CRExecution
- 测试查询section包含gloabl的config数据
 - 属性vision @0
 - 属性owner @0
 - 属性module @0
 - 属性section @0
 - 属性key @0
- 测试查询参数包含section的config数据
 - 属性vision @rnd
 - 属性owner @system
 - 属性module @common
 - 属性section @global
 - 属性key @CRExecution
- 测试查询vision包含rnd的config数据
 - 属性vision @rnd
 - 属性owner @system
 - 属性module @common
 - 属性section @global
 - 属性key @CRExecution
- 什么参数都不传，查看返回的config
 - 属性vision @rnd
 - 属性owner @system
 - 属性module @common
 - 属性section @global
 - 属性key @CRExecution
- 测试查询参数包含vision、owner、module、section、key的config数据
 - 属性vision @rnd
 - 属性owner @system
 - 属性module @common
 - 属性section @global
 - 属性key @version
- 测试删除参数包含vision、owner、module、key的config数据 @1
- 测试删除参数包含owner、module、section、key的config数据 @1
- 测试删除参数包含owner、module、key的config数据 @1
- 测试删除参数包含module、section、key的config数据 @1
- 测试删除参数包含module、key的config数据 @1
- 测试删除参数包含section、key的config数据 @1
- 测试删除参数包含key的config数据 @1
- 测试删除参数包含module的config数据 @0
- 测试删除参数包含section的config数据 @0
- 测试删除参数包含owner的config数据 @0
- 测试删除参数包含vision的config数据 @0

*/

$paramString     = array();
$paramString[0]  = "vision=rnd&owner=system&module=common&section=global&key=version";
$paramString[1]  = "vision=rnd&owner=system&module=common&key=hourPoint";
$paramString[2]  = "owner=system&module=common&section=global&key=mode";
$paramString[3]  = "owner=system&module=common&key=CRProduct";
$paramString[4]  = "module=common&section=global&key=sn";
$paramString[5]  = "module=common&key=CRExecution";
$paramString[6]  = "key=URSR";
$paramString[7]  = "module=common";
$paramString[8]  = "section=gloabl";
$paramString[9]  = "owner=system";
$paramString[10] = "vision=rnd";
$paramString[11] = "";

$method = array('select', 'delete');
$print  = 'vision,owner,module,section,key';

$setting = new settingModelTest();

r($setting->createDAOTest($paramString[0],  $method[0])) && p('vision,owner,module,section,key') && e('rnd,system,common,global,version');     //测试查询参数包含vision、owner、module、section、key的config数据
r($setting->createDAOTest($paramString[1],  $method[0])) && p('vision,owner,module,section,key') && e('rnd,system,common,global,hourPoint');   //测试查询参数包含vision、owner、module、key的config数据
r($setting->createDAOTest($paramString[2],  $method[0])) && p('vision,owner,module,section,key') && e('rnd,system,common,global,mode');        //测试查询参数包含owner、module、section、key的config数据
r($setting->createDAOTest($paramString[3],  $method[0])) && p('vision,owner,module,section,key') && e('rnd,system,common,global,CRProduct');   //测试查询参数包含owner、module、key的config数据
r($setting->createDAOTest($paramString[4],  $method[0])) && p('vision,owner,module,section,key') && e('rnd,system,common,global,sn');          //测试查询参数包含module、section、key的config数据
r($setting->createDAOTest($paramString[5],  $method[0])) && p('vision,owner,module,section,key') && e('rnd,system,common,global,CRExecution'); //测试查询参数包含module、key的config数据
r($setting->createDAOTest($paramString[6],  $method[0])) && p('vision,owner,module,section,key') && e('rnd,system,common,global,URSR');        //测试查询参数包含section、key的config数据
r($setting->createDAOTest($paramString[7],  $method[0])) && p('vision,owner,module,section,key') && e('rnd,system,common,global,CRExecution'); //测试查询module=common的config数据
r($setting->createDAOTest($paramString[8],  $method[0])) && p('vision,owner,module,section,key') && e('0,0,0,0,0');                            //测试查询section包含gloabl的config数据
r($setting->createDAOTest($paramString[9],  $method[0])) && p('vision,owner,module,section,key') && e('rnd,system,common,global,CRExecution'); //测试查询参数包含section的config数据
r($setting->createDAOTest($paramString[10], $method[0])) && p('vision,owner,module,section,key') && e('rnd,system,common,global,CRExecution'); //测试查询vision包含rnd的config数据
r($setting->createDAOTest($paramString[11], $method[0])) && p('vision,owner,module,section,key') && e('rnd,system,common,global,CRExecution'); //什么参数都不传，查看返回的config
r($setting->createDAOTest($paramString[0],  $method[0])) && p('vision,owner,module,section,key') && e('rnd,system,common,global,version');     //测试查询参数包含vision、owner、module、section、key的config数据
r($setting->createDAOTest($paramString[1],  $method[1])) && p()                                  && e('1');                                    //测试删除参数包含vision、owner、module、key的config数据
r($setting->createDAOTest($paramString[2],  $method[1])) && p()                                  && e('1');                                    //测试删除参数包含owner、module、section、key的config数据
r($setting->createDAOTest($paramString[3],  $method[1])) && p()                                  && e('1');                                    //测试删除参数包含owner、module、key的config数据
r($setting->createDAOTest($paramString[4],  $method[1])) && p()                                  && e('1');                                    //测试删除参数包含module、section、key的config数据
r($setting->createDAOTest($paramString[5],  $method[1])) && p()                                  && e('1');                                    //测试删除参数包含module、key的config数据
r($setting->createDAOTest($paramString[6],  $method[1])) && p()                                  && e('1');                                    //测试删除参数包含section、key的config数据
r($setting->createDAOTest($paramString[7],  $method[1])) && p()                                  && e('1');                                    //测试删除参数包含key的config数据
r($setting->createDAOTest($paramString[8],  $method[1])) && p()                                  && e('0');                                    //测试删除参数包含module的config数据
r($setting->createDAOTest($paramString[9],  $method[1])) && p()                                  && e('0');                                    //测试删除参数包含section的config数据
r($setting->createDAOTest($paramString[10], $method[1])) && p()                                  && e('0');                                    //测试删除参数包含owner的config数据
r($setting->createDAOTest($paramString[11], $method[1])) && p()                                  && e('0');                                    //测试删除参数包含vision的config数据