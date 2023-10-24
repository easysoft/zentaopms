#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->setWIP();
cid=1
pid=1

测试设置看板列401的在制品限制 >> Backlog,-1
测试设置看板列402的在制品限制 >> 准备好,100
测试设置看板列404的在制品限制 >> 进行中,100
测试设置看板列405的在制品限制 >> 完成,100
测试设置看板列403的在制品限制 >> 开发,220
测试设置看板列406的在制品限制 子列未设置在制品数量 >> 请先设置子列的在制品数量后再操作
测试设置看板列407的在制品限制 >> 进行中,100
测试设置看板列408的在制品限制 >> 完成,100
测试设置看板列406的在制品限制 父列小于子列 >> 父列的在制品数量不能小于子列在制品数量之和
测试设置看板列406的在制品限制 >> 测试,220
测试设置看板列408的在制品限制 子列大于父列 >> 子列在制品数量之和不能大于父列的在制品数量
测试设置看板列401的在制品限制 非正整数 >> 在制品数量必须是正整数。

*/

$columnIDList = array('401', '402', '403', '404', '405', '406', '407', '408');
$limitList    = array('-1', '0', '100', '150', '220');
$WIPCountList = array('0', '100', '150', '220');
$noLimit      = -1;

$kanban = new kanbanTest();

r($kanban->setWIPTest($columnIDList[0], $limitList[0], $noLimit))         && p('name,limit') && e('Backlog,-1');                                 // 测试设置看板列401的在制品限制
r($kanban->setWIPTest($columnIDList[1], $limitList[2], $WIPCountList[1])) && p('name,limit') && e('准备好,100');                                 // 测试设置看板列402的在制品限制
r($kanban->setWIPTest($columnIDList[3], $limitList[2], $WIPCountList[1])) && p('name,limit') && e('进行中,100');                                 // 测试设置看板列404的在制品限制
r($kanban->setWIPTest($columnIDList[4], $limitList[2], $WIPCountList[1])) && p('name,limit') && e('完成,100');                                   // 测试设置看板列405的在制品限制
r($kanban->setWIPTest($columnIDList[2], $limitList[4], $WIPCountList[3])) && p('name,limit') && e('开发,220');                                   // 测试设置看板列403的在制品限制
r($kanban->setWIPTest($columnIDList[5], $limitList[4], $WIPCountList[1])) && p('limit')      && e('请先设置子列的在制品数量后再操作');           // 测试设置看板列406的在制品限制 子列未设置在制品数量
r($kanban->setWIPTest($columnIDList[6], $limitList[2], $WIPCountList[1])) && p('name,limit') && e('进行中,100');                                 // 测试设置看板列407的在制品限制
r($kanban->setWIPTest($columnIDList[7], $limitList[2], $WIPCountList[1])) && p('name,limit') && e('完成,100');                                   // 测试设置看板列408的在制品限制
r($kanban->setWIPTest($columnIDList[5], $limitList[3], $WIPCountList[2])) && p('limit')      && e('父列的在制品数量不能小于子列在制品数量之和'); // 测试设置看板列406的在制品限制 父列小于子列
r($kanban->setWIPTest($columnIDList[5], $limitList[4], $WIPCountList[3])) && p('name,limit') && e('测试,220');                                   // 测试设置看板列406的在制品限制
r($kanban->setWIPTest($columnIDList[7], $limitList[4], $WIPCountList[3])) && p('limit')      && e('子列在制品数量之和不能大于父列的在制品数量'); // 测试设置看板列408的在制品限制 子列大于父列
r($kanban->setWIPTest($columnIDList[0], $limitList[1], $WIPCountList[0])) && p('limit')      && e('在制品数量必须是正整数。');                   // 测试设置看板列401的在制品限制 非正整数
