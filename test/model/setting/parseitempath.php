#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/setting.class.php';
su('admin');

/**

title=测试 settingModel->parseItemPath();
cid=1
pid=1

解析参数包含vision、owner、module、section、key的数据 >> rnd,system,common,global,version
解析参数包含vision、owner、module、key的数据 >> rnd,system,common,,hourPoint
解析参数包含owner、module、section、key的数据 >> system,common,global,mode
解析参数包含owner、module、key的数据 >> system,common,,CRProduct

*/

$setting = new settingTest();

$params     = array();
$params[0]  = "system.common.global.version@rnd";
$params[1]  = "system.common.hourPoint@rnd";
$params[2]  = "system.common.global.mode";
$params[3]  = "system.common.CRProduct";

r($setting->parseItemPathTest($params[0]))  && p('vision,owner,module,section,key') && e('rnd,system,common,global,version'); // 解析参数包含vision、owner、module、section、key的数据
r($setting->parseItemPathTest($params[1]))  && p('vision,owner,module,section,key') && e('rnd,system,common,,hourPoint');     // 解析参数包含vision、owner、module、key的数据
r($setting->parseItemPathTest($params[2]))  && p('owner,module,section,key')        && e('system,common,global,mode');        // 解析参数包含owner、module、section、key的数据
r($setting->parseItemPathTest($params[3]))  && p('owner,module,section,key')        && e('system,common,,CRProduct');         // 解析参数包含owner、module、key的数据
