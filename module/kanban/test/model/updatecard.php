#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancard')->gen(1);

/**

title=测试 kanbanModel->updateCard();
timeout=0
cid=1

- 正常编辑卡片
 - 属性name @修改名字
 - 属性estimate @3
 - 属性progress @20
 - 属性pri @1
- 卡片的预计不能是负数属性estimate @预计不能为负数!
- 测试进度小于0的情况属性progress @请输入正确的进度
- 测试进度大于100的情况属性progress @请输入正确的进度
- 测试开始日期大于结束日期的情况属性end @"截止日期"不能小于"预计开始"!

*/

$card1 = new stdClass();
$card1->name     = '修改名字';
$card1->estimate = 3;
$card1->progress = 20;
$card1->pri      = 1;
$card1->begin    = '2022-01-01';
$card1->end      = '2022-03-01';

$card2 = clone $card1;
$card2->desc     = '修改描述';
$card2->estimate = -1;

$card3 = clone $card1;
$card3->progress = -10;

$card4 = clone $card1;
$card4->progress = 110;

$card5 = clone $card1;
$card5->begin = '2022-03-30';
$card5->end   = '2022-03-01';

$kanban = new kanbanTest();

r($kanban->updateCardTest(1, $card1)) && p('name,estimate,progress,pri') && e('修改名字,3,20,1');               // 正常编辑卡片
r($kanban->updateCardTest(1, $card2)) && p('estimate')                   && e('预计不能为负数!');               // 卡片的预计不能是负数
r($kanban->updateCardTest(1, $card3)) && p('progress')                   && e('请输入正确的进度');              // 测试进度小于0的情况
r($kanban->updateCardTest(1, $card4)) && p('progress')                   && e('请输入正确的进度');              // 测试进度大于100的情况
r($kanban->updateCardTest(1, $card5)) && p('end')                        && e('"截止日期"不能小于"预计开始"!'); // 测试开始日期大于结束日期的情况