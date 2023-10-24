#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/holiday.class.php';
su('admin');

/**

title=测试 holidayModel->create();
cid=1
pid=1

测试修改类型 >> true
测试修改名称 >> true
测试修改描述 >> true
测试将必填项名称置空 >> 『名称』不能为空。
测试将必填项开始日期置空 >> 『开始日期』应当为合法的日期。
测试将必填项结束日期置空 >> 『结束日期』应当为合法的日期。
测试输入大于开始日期的结束日期 >> 『结束日期』应当不小于

*/

$holidayIDList = array('1', '2');

$holiday = new holidayTest();

$updateType      = array('type'  => 'working');
$updateName      = array('name'  => '修改holiday的名字');
$updateDesc      = array('desc'  => '一些描述');
$noName          = array('name'  => '');
$noBegin         = array('begin' => '');
$noEnd           = array('end'   => '');
$endltBegin      = array('begin' => '2022-05-08', 'end' => '2022-05-01');

r($holiday->updateTest($holidayIDList[0], $updateType))   && p()          && e('true'); //测试修改类型
r($holiday->updateTest($holidayIDList[0], $updateName))   && p()          && e('true'); //测试修改名称
r($holiday->updateTest($holidayIDList[0], $updateDesc))   && p()          && e('true'); //测试修改描述
r($holiday->updateTest($holidayIDList[0], $noName))       && p('name:0')  && e('『名称』不能为空。'); //测试将必填项名称置空
r($holiday->updateTest($holidayIDList[0], $noBegin))      && p('begin:0') && e('『开始日期』应当为合法的日期。'); //测试将必填项开始日期置空
r($holiday->updateTest($holidayIDList[0], $noEnd))        && p('end:0')   && e('『结束日期』应当为合法的日期。'); //测试将必填项结束日期置空
r($holiday->updateTest($holidayIDList[0], $endltBegin))   && p('end:0')   && e('『结束日期』应当不小于'); //测试输入大于开始日期的结束日期

