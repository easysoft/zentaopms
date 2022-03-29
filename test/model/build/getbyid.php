#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/build.class.php';
su('admin');

/**

title=测试 buildModel->getByID();
cid=1
pid=1

项目版本查询 >> 项目版本版本7
执行版本查询 >> 执行版本版本17
无id查询 >> 0
图片字段传字符串测试 >> 17

*/

$buildID = array('7', '17');

$build = new buildTest();

r($build->getByIDTest($buildID[0], true))   && p('name')    && e('项目版本版本7');  //项目版本查询
r($build->getByIDTest($buildID[1], false))  && p('name')    && e('执行版本版本17'); //执行版本查询
r($build->getByIDTest('', true))            && p()          && e('0');              //无id查询
r($build->getByIDTest($buildID[0], 'test')) && p('project') && e('17');             //图片字段传字符串测试