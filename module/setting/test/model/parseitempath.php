#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('config')->gen(7);

/**

title=测试 settingModel->parseItemPath();
timeout=0
cid=18366

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
 - 属性owner @system
 - 属性module @common
 - 属性section @global
 - 属性key @mode
- 解析参数包含owner、module、key的数据
 - 属性owner @system
 - 属性module @common
 - 属性section @~~
 - 属性key @CRProduct

*/

$setting = new settingModelTest();

$params     = array();
$params[0]  = "system.common.global.version@rnd";
$params[1]  = "system.common.hourPoint@rnd";
$params[2]  = "system.common.global.mode";
$params[3]  = "system.common.CRProduct";

r($setting->parseItemPathTest($params[0]))  && p('vision,owner,module,section,key') && e('rnd,system,common,global,version'); // 解析参数包含vision、owner、module、section、key的数据
r($setting->parseItemPathTest($params[1]))  && p('vision,owner,module,section,key') && e('rnd,system,common,~~,hourPoint');   // 解析参数包含vision、owner、module、key的数据
r($setting->parseItemPathTest($params[2]))  && p('owner,module,section,key')        && e('system,common,global,mode');        // 解析参数包含owner、module、section、key的数据
r($setting->parseItemPathTest($params[3]))  && p('owner,module,section,key')        && e('system,common,~~,CRProduct');       // 解析参数包含owner、module、key的数据