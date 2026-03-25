#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);

$card = zenData('kanbancard');
$card->id->range('1-8');
$card->kanban->range('1');
$card->region->range('1');
$card->group->range('1');
$card->name->range('卡片1,卡片2,卡片3,卡片4,卡片5,卡片6,卡片7,卡片8');
$card->assignedTo->range('admin,admin,user1,user2,,user3,user1,user2');
$card->status->range('doing,doing,doing,doing,doing,doing,done,doing');
$card->archived->range('0,0,0,0,0,1,0,0');
$card->deleted->range('0,0,0,0,0,0,0,1');
$card->end->range('`' . date('Y-m-d', strtotime('+1 day')) . '`,`' . date('Y-m-d', strtotime('+2 day')) . '`,`' . date('Y-m-d', strtotime('+3 day')) . '`,`' . date('Y-m-d', strtotime('+10 day')) . '`,`' . date('Y-m-d', strtotime('+1 day')) . '`,`' . date('Y-m-d', strtotime('+1 day')) . '`,`' . date('Y-m-d', strtotime('+1 day')) . '`,`' . date('Y-m-d', strtotime('+1 day')) . '`');
$card->gen(8);

su('admin');

/**

title=测试 reportModel->getUserKanbanCards();
timeout=0
cid=18191

- 获取admin的卡片数量 @2
- 获取admin的第一张卡片ID第0条的id属性 @1
- 获取user3的卡片数量 @1
- 获取user3的第一张卡片ID和名称
 - 第0条的id属性 @5
 - 第0条的name属性 @卡片5

*/

$report = new reportModelTest();
$result = $report->getUserKanbanCardsTest();

r(count($result['admin'])) && p()       && e('2'); // 获取admin的卡片数量
r($result['admin'])        && p('0:id') && e('1'); // 获取admin的第一张卡片ID
r(count($result['user3'])) && p()       && e('1'); // 获取user3的卡片数量
r($result['user3'])        && p('0:id,name') && e('5,卡片5'); // 获取user3的第一张卡片ID和名称