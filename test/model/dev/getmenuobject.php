#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dev.class.php';

/**

title=测试 devModel::getMenuObject();
cid=1
pid=1

获取菜单对象 >> my

*/

global $tester;
$tester->loadModel('dev');
r($tester->dev->getMenuObject('标题', 'my', 'index'))  && p('module') && e('my'); //获取菜单对象
