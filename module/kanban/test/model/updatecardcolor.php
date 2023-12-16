#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancard')->gen(5);

/**

title=- 属性color @
timeout=0
cid=2

- 更新卡片1的颜色
 - 属性id @1
 - 属性name @卡片1
 - 属性color @b10b0b
- 更新卡片2的颜色
 - 属性id @2
 - 属性name @卡片2
 - 属性color @cfa227
- 更新卡片3的颜色
 - 属性id @3
 - 属性name @卡片3
 - 属性color @2a5f29
- 更新卡片4的颜色
 - 属性id @4
 - 属性name @卡片4
 - 属性color @b10b0b
- 更新卡片5的颜色
 - 属性id @5
 - 属性name @卡片5
 - 属性color @cfa227

*/

$cardIDList = array('1', '2', '3', '4', '5');
$colorList  = array('b10b0b', 'cfa227', '2a5f29');

$kanban = new kanbanTest();

$card1 = $kanban->updateCardColorTest($cardIDList[0], $colorList[0]);
$card2 = $kanban->updateCardColorTest($cardIDList[1], $colorList[1]);
$card3 = $kanban->updateCardColorTest($cardIDList[2], $colorList[2]);
$card4 = $kanban->updateCardColorTest($cardIDList[3], $colorList[0]);
$card5 = $kanban->updateCardColorTest($cardIDList[4], $colorList[1]);

$card1->color = str_replace('#', '', $card1->color);
$card2->color = str_replace('#', '', $card2->color);
$card3->color = str_replace('#', '', $card3->color);
$card4->color = str_replace('#', '', $card4->color);
$card5->color = str_replace('#', '', $card5->color);

r($card1) && p('id,name,color') && e('1,卡片1,b10b0b'); // 更新卡片1的颜色
r($card2) && p('id,name,color') && e('2,卡片2,cfa227'); // 更新卡片2的颜色
r($card3) && p('id,name,color') && e('3,卡片3,2a5f29'); // 更新卡片3的颜色
r($card4) && p('id,name,color') && e('4,卡片4,b10b0b'); // 更新卡片4的颜色
r($card5) && p('id,name,color') && e('5,卡片5,cfa227'); // 更新卡片5的颜色