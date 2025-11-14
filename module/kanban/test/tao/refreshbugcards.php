#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::refreshBugCards();
timeout=0
cid=16989

- 执行kanbanTest模块的refreshBugCardsTest方法，参数是$cardPairs0, 999, ''  @7
- 执行kanbanTest模块的refreshBugCardsTest方法，参数是$cardPairs1, 101, ''
 - 属性unconfirmed @,1,
- 执行kanbanTest模块的refreshBugCardsTest方法，参数是$cardPairs2, 101, ''
 - 属性confirmed @,2,3,
- 执行kanbanTest模块的refreshBugCardsTest方法，参数是$cardPairs3, 101, ''
 - 属性fixing @,4,
- 执行kanbanTest模块的refreshBugCardsTest方法，参数是$cardPairs4, 101, ''
 - 属性fixed @,5,6,7,
- 执行kanbanTest模块的refreshBugCardsTest方法，参数是$cardPairs5, 101, ''
 - 属性closed @,8,9,10,

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

$bug = zenData('bug');
$bug->id->range('1-10');
$bug->execution->range('101');
$bug->status->range('active{4},resolved{3},closed{3}');
$bug->confirmed->range('0,1,1,1,0,1,1,1,1,1');
$bug->activatedCount->range('0,0,0,1,0,0,0,0,0,0');
$bug->title->range('Bug title');
$bug->gen(10);

su('admin');

$kanbanTest = new kanbanTest();

$cardPairs0 = array('unconfirmed' => '', 'confirmed' => '', 'fixing' => '', 'fixed' => '', 'testing' => '', 'tested' => '', 'closed' => '');
$cardPairs1 = array('unconfirmed' => '', 'confirmed' => '', 'fixing' => '', 'fixed' => '', 'testing' => '', 'tested' => '', 'closed' => '');
$cardPairs2 = array('unconfirmed' => '', 'confirmed' => '', 'fixing' => '', 'fixed' => '', 'testing' => '', 'tested' => '', 'closed' => '');
$cardPairs3 = array('unconfirmed' => '', 'confirmed' => '', 'fixing' => '', 'fixed' => '', 'testing' => '', 'tested' => '', 'closed' => '');
$cardPairs4 = array('unconfirmed' => '', 'confirmed' => '', 'fixing' => '', 'fixed' => '', 'testing' => '', 'tested' => '', 'closed' => '');
$cardPairs5 = array('unconfirmed' => '', 'confirmed' => '', 'fixing' => '', 'fixed' => '', 'testing' => '', 'tested' => '', 'closed' => '');

r(count($kanbanTest->refreshBugCardsTest($cardPairs0, 999, ''))) && p() && e('0');
r($kanbanTest->refreshBugCardsTest($cardPairs1, 101, '')) && p('unconfirmed') && e(',1,');
r($kanbanTest->refreshBugCardsTest($cardPairs2, 101, '')) && p('confirmed') && e(',2,3,');
r($kanbanTest->refreshBugCardsTest($cardPairs3, 101, '')) && p('fixing') && e(',4,');
r($kanbanTest->refreshBugCardsTest($cardPairs4, 101, '')) && p('fixed') && e(',5,6,7,');
r($kanbanTest->refreshBugCardsTest($cardPairs5, 101, '')) && p('closed') && e(',8,9,10,');