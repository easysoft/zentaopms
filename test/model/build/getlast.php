#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/build.class.php';
su('admin');

/**

title=测试 buildModel->getLast();
cid=1
pid=1

执行id为空 >> 10
执行id不存在 >> 0
执行id正常存在 >> 执行版本版本17

*/

$executionIDList = array('', '7', '107');

$build = new buildTest();

r($build->getLastTest($executionIDList[0])) && p('id')   && e('10');            //执行id为空
r($build->getLastTest($executionIDList[1])) && p()       && e('0');             //执行id不存在
r($build->getLastTest($executionIDList[2])) && p('name') && e('执行版本版本17');//执行id正常存在