#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->updateCardColor();
cid=1
pid=1

更新卡片1的颜色 >> 1,卡片1,#b10b0b
更新卡片2的颜色 >> 2,卡片2,#cfa227
更新卡片3的颜色 >> 3,卡片3,#2a5f29
更新卡片4的颜色 >> 4,卡片4,#b10b0b
更新卡片5的颜色 >> 5,卡片5,#cfa227

*/

$cardIDList = array('1', '2', '3', '4', '5');
$colorList  = array('b10b0b', 'cfa227', '2a5f29');

$kanban = new kanbanTest();

r($kanban->updateCardColorTest($cardIDList[0], $colorList[0])) && p('id,name,color') && e('1,卡片1,#b10b0b'); // 更新卡片1的颜色
r($kanban->updateCardColorTest($cardIDList[1], $colorList[1])) && p('id,name,color') && e('2,卡片2,#cfa227'); // 更新卡片2的颜色
r($kanban->updateCardColorTest($cardIDList[2], $colorList[2])) && p('id,name,color') && e('3,卡片3,#2a5f29'); // 更新卡片3的颜色
r($kanban->updateCardColorTest($cardIDList[3], $colorList[0])) && p('id,name,color') && e('4,卡片4,#b10b0b'); // 更新卡片4的颜色
r($kanban->updateCardColorTest($cardIDList[4], $colorList[1])) && p('id,name,color') && e('5,卡片5,#cfa227'); // 更新卡片5的颜色