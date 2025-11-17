#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('config')->gen(5);

/**

title=测试 commonModel::loadConfigFromDB();
timeout=0
cid=15686

- 查看设置后的第一条配置项详情
 - 属性owner @system
 - 属性module @common
 - 属性section @global
 - 属性key @hourPoint
 - 属性value @0
- 查看设置后的第二条配置项详情
 - 属性owner @system
 - 属性module @common
 - 属性section @global
 - 属性key @CRProduct
 - 属性value @1

*/

su('admin');
$tester->loadModel('common')->loadConfigFromDB();

r($config->systemDB->common[0]) && p('owner,module,section,key,value') && e('system,common,global,hourPoint,0'); // 查看设置后的第一条配置项详情
r($config->systemDB->common[1]) && p('owner,module,section,key,value') && e('system,common,global,CRProduct,1'); // 查看设置后的第二条配置项详情