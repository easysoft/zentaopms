#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dev.class.php';
su('admin');

/**

title=测试 devModel::getAPIs();
cid=1
pid=1

获取todo模块api名字 >> create
获取product模块api名字 >> index
获取project模块api描述 >> Project create guide.

*/

$dev = new devTest();
r($dev->getAPIsTest('todo'))    && p('0:name') && e('create');                //获取todo模块api名字
r($dev->getAPIsTest('product')) && p('0:name') && e('index');                 //获取product模块api名字
r($dev->getAPIsTest('project')) && p('0:desc') && e('Project create guide.'); //获取project模块api描述