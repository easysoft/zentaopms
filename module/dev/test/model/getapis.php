#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/dev.class.php';
su('admin');

/**

title=测试 devModel::getAPIs();
cid=1
pid=1

获取todo模块第一个函数名字 >> create
获取product模块第一个函数名字 >> index
获取project模块第一个函数描述 >> 创建项目引导。

*/

$dev = new devTest();
r($dev->getAPIsTest('todo'))    && p('0:name') && e('create');         //获取todo模块第一个函数名字
r($dev->getAPIsTest('product')) && p('0:name') && e('index');          //获取product模块第一个函数名字
r($dev->getAPIsTest('project')) && p('0:desc') && e('创建项目引导。'); //获取project模块第一个函数描述
