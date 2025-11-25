#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::refreshERURCards();
timeout=0
cid=16990

- 执行kanbanTest模块的refreshERURCardsTest方法，参数是$cardPairs0, 999, '', 'story'  @7
- 执行kanbanTest模块的refreshERURCardsTest方法，参数是$cardPairs1, 101, '', 'story'
 - 属性wait @,2,1,
- 执行kanbanTest模块的refreshERURCardsTest方法，参数是$cardPairs2, 101, '', 'story'
 - 属性planned @,3,
- 执行kanbanTest模块的refreshERURCardsTest方法，参数是$cardPairs3, 101, '', 'story' 属性projected @~~
- 执行kanbanTest模块的refreshERURCardsTest方法，参数是$cardPairs4, 101, '', 'story' 属性developing @~~
- 执行kanbanTest模块的refreshERURCardsTest方法，参数是$cardPairs5, 101, '', 'story' 属性delivering @~~
- 执行kanbanTest模块的refreshERURCardsTest方法，参数是$cardPairs6, 101, '', 'story' 属性delivered @~~
- 执行kanbanTest模块的refreshERURCardsTest方法，参数是$cardPairs7, 101, '', 'parentStory'
 - 属性wait @,10,9,
- 执行kanbanTest模块的refreshERURCardsTest方法，参数是$cardPairs8, 101, '', 'epic'
 - 属性wait @,8,7,6,

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

su('admin');

$kanbanTest = new kanbanTest();

$cardPairs0 = array('wait' => '', 'planned' => '', 'projected' => '', 'developing' => '', 'delivering' => '', 'delivered' => '', 'closed' => '');
$cardPairs1 = array('wait' => '', 'planned' => '', 'projected' => '', 'developing' => '', 'delivering' => '', 'delivered' => '', 'closed' => '');
$cardPairs2 = array('wait' => '', 'planned' => '', 'projected' => '', 'developing' => '', 'delivering' => '', 'delivered' => '', 'closed' => '');
$cardPairs3 = array('wait' => '', 'planned' => '', 'projected' => '', 'developing' => '', 'delivering' => '', 'delivered' => '', 'closed' => '');
$cardPairs4 = array('wait' => '', 'planned' => '', 'projected' => '', 'developing' => '', 'delivering' => '', 'delivered' => '', 'closed' => '');
$cardPairs5 = array('wait' => '', 'planned' => '', 'projected' => '', 'developing' => '', 'delivering' => '', 'delivered' => '', 'closed' => '');
$cardPairs6 = array('wait' => '', 'planned' => '', 'projected' => '', 'developing' => '', 'delivering' => '', 'delivered' => '', 'closed' => '');
$cardPairs7 = array('wait' => '', 'planned' => '', 'projected' => '', 'developing' => '', 'delivering' => '', 'delivered' => '', 'closed' => '');
$cardPairs8 = array('wait' => '', 'planned' => '', 'projected' => '', 'developing' => '', 'delivering' => '', 'delivered' => '', 'closed' => '');

r(count($kanbanTest->refreshERURCardsTest($cardPairs0, 999, '', 'story'))) && p() && e('7');
r($kanbanTest->refreshERURCardsTest($cardPairs1, 101, '', 'story')) && p('wait') && e(',2,1,');
r($kanbanTest->refreshERURCardsTest($cardPairs2, 101, '', 'story')) && p('planned') && e(',3,');
r($kanbanTest->refreshERURCardsTest($cardPairs3, 101, '', 'story')) && p('projected') && e('~~');
r($kanbanTest->refreshERURCardsTest($cardPairs4, 101, '', 'story')) && p('developing') && e('~~');
r($kanbanTest->refreshERURCardsTest($cardPairs5, 101, '', 'story')) && p('delivering') && e('~~');
r($kanbanTest->refreshERURCardsTest($cardPairs6, 101, '', 'story')) && p('delivered') && e('~~');
r($kanbanTest->refreshERURCardsTest($cardPairs7, 101, '', 'parentStory')) && p('wait') && e(',10,9,');
r($kanbanTest->refreshERURCardsTest($cardPairs8, 101, '', 'epic')) && p('wait') && e(',8,7,6,');