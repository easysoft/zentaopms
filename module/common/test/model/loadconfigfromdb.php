#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('config')->gen(5);

/**

title=测试 commonModel::loadConfigFromDB();
timeout=0
cid=1

- 查看设置后的配置项数量 @5
- 查看设置后的配置项详情
 - 第0条的owner属性 @system
 - 第0条的module属性 @common
 - 第0条的section属性 @global
 - 第0条的key属性 @hourPoint
 - 第0条的value属性 @0

*/

su('admin');
$tester->loadModel('common')->loadConfigFromDB();

r(count($config->system->common)) && p() && e(5); // 查看设置后的配置项数量
r($config->system->common) && p('0:owner,module,section,key,value') && e('system,common,global,hourPoint,0'); // 查看设置后的配置项详情