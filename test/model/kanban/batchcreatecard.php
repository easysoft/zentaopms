#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->batchCreateCard();
cid=1
pid=1

批量创建卡片 其中一个没有名称 >> 1,1,1,common,,1003,1002,1001,1,2,801,
批量创建卡片 预计为负数 >> 预计不能为负数!
批量创建看板 截止日期小于开始日期 >> "截止日期"不能小于"预计开始"!
批量创建看板 >> 1,1,1,common,,1010,1009,1008,1007,1004,1003,1002,1001,1,2,801,

*/

$no_name    = array('0' => '', '1' => '批量创建的卡片2', '2' => '批量创建的卡片3', '3' => '批量创建的卡片4');
$estimate   = array('0' => '1', '1' => '-1', '2' => '-2', '3' => '-3');
$endDitto   = array('1' => 'off', '2' => 'off', '3' => 'off', '4' => 'off');

$kanban = new kanbanTest();

r($kanban->batchCreateCardTest(array('name' => $no_name)))      && p('kanban,column,lane,type,cards') && e('1,1,1,common,,1003,1002,1001,1,2,801,');                      // 批量创建卡片 其中一个没有名称
r($kanban->batchCreateCardTest(array('estimate' => $estimate))) && p()                                && e('预计不能为负数!');                                     // 批量创建卡片 预计为负数
r($kanban->batchCreateCardTest(array(), true))                  && p()                                && e('"截止日期"不能小于"预计开始"!');                       // 批量创建看板 截止日期小于开始日期
r($kanban->batchCreateCardTest())                               && p('kanban,column,lane,type,cards') && e('1,1,1,common,,1010,1009,1008,1007,1004,1003,1002,1001,1,2,801,');  // 批量创建看板
