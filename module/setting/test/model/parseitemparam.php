#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/setting.unittest.class.php';
su('admin');

zenData('config')->gen(7);

/**

title=测试 settingModel->parseItemParam();
timeout=0
cid=18365

- 解析参数包含vision、owner、module、section、key的数据
 - 属性vision @rnd
 - 属性owner @system
 - 属性module @common
 - 属性section @global
 - 属性key @version
- 解析参数包含vision、owner、module、key的数据
 - 属性vision @rnd
 - 属性owner @system
 - 属性module @common
 - 属性section @~~
 - 属性key @hourPoint
- 解析参数包含owner、module、section、key的数据
 - 属性vision @~~
 - 属性owner @system
 - 属性module @common
 - 属性section @global
 - 属性key @mode
- 解析参数包含owner、module、key的数据
 - 属性vision @~~
 - 属性owner @system
 - 属性module @common
 - 属性section @~~
 - 属性key @CRProduct
- 解析参数包含module、section、key的数据
 - 属性vision @~~
 - 属性owner @~~
 - 属性module @common
 - 属性section @global
 - 属性key @sn
- 解析参数包含module、key的数据
 - 属性vision @~~
 - 属性owner @~~
 - 属性module @common
 - 属性section @~~
 - 属性key @CRExection
- 解析参数包含section、key的数据
 - 属性vision @~~
 - 属性owner @~~
 - 属性module @~~
 - 属性section @safe
 - 属性key @mode
- 解析参数包含key的数据
 - 属性vision @~~
 - 属性owner @~~
 - 属性module @~~
 - 属性section @~~
 - 属性key @URSR
- 解析参数包含module的数据
 - 属性vision @~~
 - 属性owner @~~
 - 属性module @story
 - 属性section @~~
 - 属性key @~~
- 解析参数包含section的数据
 - 属性vision @~~
 - 属性owner @~~
 - 属性module @~~
 - 属性section @xuanxuan
 - 属性key @~~
- 解析参数包含owner的数据
 - 属性vision @~~
 - 属性owner @admin
 - 属性module @~~
 - 属性section @~~
 - 属性key @~~
- 解析参数包含vision的数据
 - 属性vision @rnd
 - 属性owner @~~
 - 属性module @~~
 - 属性section @~~
 - 属性key @~~
- 解析空数据
 - 属性vision @~~
 - 属性owner @~~
 - 属性module @~~
 - 属性section @~~
 - 属性key @~~

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

r($setting->parseItemParamTest($params[0]))  && p('vision,owner,module,section,key') && e('rnd,system,common,global,version'); // 解析参数包含vision、owner、module、section、key的数据
r($setting->parseItemParamTest($params[1]))  && p('vision,owner,module,section,key') && e('rnd,system,common,~~,hourPoint');   // 解析参数包含vision、owner、module、key的数据
r($setting->parseItemParamTest($params[2]))  && p('vision,owner,module,section,key') && e('~~,system,common,global,mode');     // 解析参数包含owner、module、section、key的数据
r($setting->parseItemParamTest($params[3]))  && p('vision,owner,module,section,key') && e('~~,system,common,~~,CRProduct');    // 解析参数包含owner、module、key的数据
r($setting->parseItemParamTest($params[4]))  && p('vision,owner,module,section,key') && e('~~,~~,common,global,sn');           // 解析参数包含module、section、key的数据
r($setting->parseItemParamTest($params[5]))  && p('vision,owner,module,section,key') && e('~~,~~,common,~~,CRExection');       // 解析参数包含module、key的数据
r($setting->parseItemParamTest($params[6]))  && p('vision,owner,module,section,key') && e('~~,~~,~~,safe,mode');               // 解析参数包含section、key的数据
r($setting->parseItemParamTest($params[7]))  && p('vision,owner,module,section,key') && e('~~,~~,~~,~~,URSR');                 // 解析参数包含key的数据
r($setting->parseItemParamTest($params[8]))  && p('vision,owner,module,section,key') && e('~~,~~,story,~~,~~');                // 解析参数包含module的数据
r($setting->parseitemparamtest($params[9]))  && p('vision,owner,module,section,key') && e('~~,~~,~~,xuanxuan,~~');             // 解析参数包含section的数据
r($setting->parseitemparamtest($params[10])) && p('vision,owner,module,section,key') && e('~~,admin,~~,~~,~~');                // 解析参数包含owner的数据
r($setting->parseitemparamtest($params[11])) && p('vision,owner,module,section,key') && e('rnd,~~,~~,~~,~~');                  // 解析参数包含vision的数据
r($setting->parseitemparamtest($params[12])) && p('vision,owner,module,section,key') && e('~~,~~,~~,~~,~~');                   // 解析空数据