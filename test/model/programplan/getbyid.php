#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/programplan.class.php';
su('admin');

/**

title=测试 programplanModel->getByID();
cid=1
pid=1

获取阶段131 >> 阶段31,project31
获取阶段132 >> 阶段32,project32
获取阶段133 >> 阶段33,project33
获取阶段134 >> 阶段34,project34
获取阶段135 >> 阶段35,project35

*/

$planIDList = array('131', '132', '133', '134', '135');

$programplan = new programplanTest();

r($programplan->getByIDTest($planIDList[0])) && p('name,code') && e('阶段31,project31'); // 获取阶段131
r($programplan->getByIDTest($planIDList[1])) && p('name,code') && e('阶段32,project32'); // 获取阶段132
r($programplan->getByIDTest($planIDList[2])) && p('name,code') && e('阶段33,project33'); // 获取阶段133
r($programplan->getByIDTest($planIDList[3])) && p('name,code') && e('阶段34,project34'); // 获取阶段134
r($programplan->getByIDTest($planIDList[4])) && p('name,code') && e('阶段35,project35'); // 获取阶段135