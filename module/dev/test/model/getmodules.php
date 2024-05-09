#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dev.unittest.class.php';
su('admin');

/**

title=测试 devModel::getModules();
cid=1
pid=1

获取所有模块的admin分组第一个   >> action
获取所有模块的product分组第一个 >> branch

*/

$dev = new devTest();
r($dev->getModulesTest()) && p('admin')   && e('action');  //获取所有模块的admin分组第一个
r($dev->getModulesTest()) && p('product') && e('branch');  //获取所有模块的product分组第一个
