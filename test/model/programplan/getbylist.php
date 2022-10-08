#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/programplan.class.php';
su('admin');

/**

title=测试 programplanModel->getByList();
cid=1
pid=1

测试获取项目11 12的信息 >> 项目1;项目2
测试获取项目13 14的信息 >> 项目3;项目4
测试获取项目15 16 17的信息 >> 项目5;项目6;项目7
测试获取项目18 19 20的信息 >> 项目8;项目9;项目10
测试获取项目21 22 23 24的信息 >> 项目11;项目12;项目13;项目14

*/

$planIDList = array(array(11, 12), array(13, 14), array(15, 16, 17), array(18, 19, 20), array(21, 22, 23, 24));

$programplan = new programplanTest();

r($programplan->getByListTest($planIDList[0])) && p('11:name;12:name')                 && e('项目1;项目2');                 // 测试获取项目11 12的信息
r($programplan->getByListTest($planIDList[1])) && p('13:name;14:name')                 && e('项目3;项目4');                 // 测试获取项目13 14的信息
r($programplan->getByListTest($planIDList[2])) && p('15:name;16:name;17:name')         && e('项目5;项目6;项目7');           // 测试获取项目15 16 17的信息
r($programplan->getByListTest($planIDList[3])) && p('18:name;19:name;20:name')         && e('项目8;项目9;项目10');          // 测试获取项目18 19 20的信息
r($programplan->getByListTest($planIDList[4])) && p('21:name;22:name;23:name;24:name') && e('项目11;项目12;项目13;项目14'); // 测试获取项目21 22 23 24的信息