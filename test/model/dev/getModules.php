#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dev.class.php';
su('admin');

/**

title=测试 devModel::getModules();
cid=1
pid=1

获取所有模块的my分组第一个 >> index
获取所有模块的product分组第三个 >> product
获取所有模块的system分组第二个 >> file

*/

$dev = new devTest();
r($dev->getModulesTest()) && p('my:0')      && e('index');   //获取所有模块的my分组第一个
r($dev->getModulesTest()) && p('product:2') && e('product'); //获取所有模块的product分组第三个
r($dev->getModulesTest()) && p('system:1')  && e('file');    //获取所有模块的system分组第二个