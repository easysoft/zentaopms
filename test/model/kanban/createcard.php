#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->createCard();
cid=1
pid=1

测试创建卡片1 >> 测试创建卡片1,1,1,测试创建卡片1的卡片描述
测试创建卡片2 >> 测试创建卡片2,2,2,测试创建卡片2的卡片描述
测试创建预计小于0的卡片 >> 预计不能为负数!
测试创建结束小于开始日期的卡片 >> "截止日期"不能小于"预计开始"!
测试创建没有名字的卡片 >> 『卡片名称』不能为空。

*/

$card1 = new stdclass();
$card1->name       = '测试创建卡片1';
$card1->pri        = '1';
$card1->estimate   = '1';
$card1->assignedTo = array('po15', 'user3');
$card1->desc       = '测试创建卡片1的卡片描述';

$card2 = new stdclass();
$card2->name       = '测试创建卡片2';
$card2->pri        = '2';
$card2->estimate   = '2';
$card2->assignedTo = array('user3');
$card2->desc       = '测试创建卡片2的卡片描述';

$card3 = new stdclass();
$card3->name       = '测试创建预计小于0的卡片';
$card3->pri        = '3';
$card3->estimate   = '-1';
$card3->assignedTo = array('po15');
$card3->desc       = '测试创建预计小于0的卡片的描述';

$card4 = new stdclass();
$card4->name       = '测试创建结束小于开始日期的卡片';
$card4->pri        = '4';
$card4->estimate   = '4';
$card4->assignedTo = array('user3');
$card4->begin      = '2022-03-29';
$card4->end        = '2022-03-20';
$card4->desc       = '测试创建结束小于开始日期的卡片的描述';

$card5 = new stdclass();
$card5->name       = '';
$card5->pri        = '1';
$card5->estimate   = '5';
$card5->assignedTo = array('po15');
$card5->desc       = '测试不填写名称的卡片的卡片描述';

$kanban = new kanbanTest();

r($kanban->createCardTest($card1)) && p('name,pri,estimate,desc') && e('测试创建卡片1,1,1,测试创建卡片1的卡片描述'); // 测试创建卡片1
r($kanban->createCardTest($card2)) && p('name,pri,estimate,desc') && e('测试创建卡片2,2,2,测试创建卡片2的卡片描述'); // 测试创建卡片2
r($kanban->createCardTest($card3)) && p('estimate')               && e('预计不能为负数!');                           // 测试创建预计小于0的卡片
r($kanban->createCardTest($card4)) && p('end')                    && e('"截止日期"不能小于"预计开始"!');             // 测试创建结束小于开始日期的卡片
r($kanban->createCardTest($card5)) && p('name:0')                 && e('『卡片名称』不能为空。');                    // 测试创建没有名字的卡片
