#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->updateCard();
cid=1
pid=1

测试修改卡片1 >> name,卡片1,修改名字;desc,,修改注释;estimate,0,3
测试修改卡片2 >> begin,2022-03-30;end,2022-04-01;progress,50,0
测试修改卡片3 >> assignedTo,admin,user5,po17,user3;progress,100,50;status,done,doing
测试修改卡片4 >> progress,0,100;status,doing,done
测试修改卡片5 >> pri,3,1
测试修改卡片开始小于结束日期 >> "截止日期"不能小于"预计开始"!
测试修改卡片预计小于0 >> 预计不能为负数!
测试修改卡片进度小于0 >> 请输入正确的进度
测试修改卡片进度大于100 >> 请输入正确的进度

*/

$cardIDList = array('1', '2', '3', '4', '5');

$name       = array('修改名字', '', '   ');
$desc       = '修改注释';
$assignedTo = array('user5', 'po17', 'user3');
$begin      = '2022-03-30';
$end        = '2022-04-01';
$pri        = '1';
$estimate   = array('3' , '0', '', '-1');
$progress   = array('0', '50', '100', '-1', '101');

$param1 = array('name' => $name[0], 'desc' => $desc, 'estimate' => $estimate[0]);
$param2 = array('begin' => $begin, 'end' => $end,  'progress' => $progress[0]);
$param3 = array('assignedTo' => $assignedTo, 'estimate' => $estimate[1], 'progress' => $progress[1]);
$param4 = array('estimate' => $estimate[2], 'progress' => $progress[2]);
$param5 = array('pri' => $pri);

$end    = '2022-01-01';
$param6 = array('begin' => $begin, 'end' => $end);
$param7 = array('estimate' => $estimate[3]);
$param8 = array('progress' => $progress[3]);
$param9 = array('progress' => $progress[4]);

$kanban = new kanbanTest();

r($kanban->updateCardTest($cardIDList[0], $param1)) && p('0:field,old,new;1:field,old,new;2:field,old,new') && e('name,卡片1,修改名字;desc,,修改注释;estimate,0,3');                      // 测试修改卡片1
r($kanban->updateCardTest($cardIDList[1], $param2)) && p('0:field,new;1:field,new;2:field,old,new')         && e('begin,2022-03-30;end,2022-04-01;progress,50,0');                        // 测试修改卡片2
r($kanban->updateCardTest($cardIDList[2], $param3)) && p('0:field,old,new;1:field,old,new;2:field,old,new') && e('assignedTo,admin,user5,po17,user3;progress,100,50;status,done,doing');  // 测试修改卡片3
r($kanban->updateCardTest($cardIDList[3], $param4)) && p('0:field,old,new;1:field,old,new')                 && e('progress,0,100;status,doing,done');                                     // 测试修改卡片4
r($kanban->updateCardTest($cardIDList[4], $param5)) && p('0:field,old,new')                                 && e('pri,3,1');                                                              // 测试修改卡片5
r($kanban->updateCardTest($cardIDList[0], $param6)) && p()                                                  && e('"截止日期"不能小于"预计开始"!'); // 测试修改卡片开始小于结束日期
r($kanban->updateCardTest($cardIDList[0], $param7)) && p()                                                  && e('预计不能为负数!'); // 测试修改卡片预计小于0
r($kanban->updateCardTest($cardIDList[0], $param8)) && p()                                                  && e('请输入正确的进度'); // 测试修改卡片进度小于0
r($kanban->updateCardTest($cardIDList[0], $param9)) && p()                                                  && e('请输入正确的进度'); // 测试修改卡片进度大于100
