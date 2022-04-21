#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';
su('admin');

/**

title=测试 personnelModel->getUserHours();
cid=1
pid=1

取出admin工时 >> 4
取出po82的工时 >> 3
取出匹配的人员数量 >> 2
当传入一个存在的角色一个不存在的角色时，打印其中存在的角色工时 >> 4
其中一个为空时，取出匹配的人员数量 >> 1
当传入task不存在时 >> 0
当task不存在时，取出匹配个数 >> 0

*/

$personnel = new personnelTest('admin');

$userTask  = array();
$userTask['po82']     = array(1);
$userTask['admin']    = array(2);

$userTask1 = array();
$userTask1['test111'] = array(1);
$userTask1['admin']   = array(2);

$userTask2 = array();
$userTask2['admin']   = array(1111);
$userTask2['po82']    = array(1112);

$result1 = $personnel->getUserHoursTest($userTask);
$result2 = count($personnel->getUserHoursTest($userTask));
$result3 = $personnel->getUserHoursTest($userTask1);
$result4 = $personnel->getUserHoursTest($userTask2);

r($result1)        && p('admin:consumed')   && e('4'); //取出admin工时
r($result1)        && p('po82:consumed')    && e('3'); //取出po82的工时
r($result2)        && p()                   && e('2'); //取出匹配的人员数量
r($result3)        && p('admin:consumed')   && e('4'); //当传入一个存在的角色一个不存在的角色时，打印其中存在的角色工时
r(count($result3)) && p()                   && e('1'); //其中一个为空时，取出匹配的人员数量
r($result4)        && p('admin:consumed')   && e('0'); //当传入task不存在时
r(count($result4)) && p()                   && e('0'); //当task不存在时，取出匹配个数