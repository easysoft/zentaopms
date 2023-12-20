#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getToAndCcList();
timeout=0
cid=1

- 获取id=1的卡片发信人员
 -  @admin
 - 属性1 @admin

*/
$kanban = new kanbanTest();

$card = $kanban->getCardByIDTest('1');
r($kanban->getToAndCcListTest($card)) && p('0,1') && e('admin,admin'); // 获取id=1的卡片发信人员