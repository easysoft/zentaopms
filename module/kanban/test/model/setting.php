#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('kanban')->gen(5);

/**

title=测试 kanbanModel->setting();
timeout=0
cid=1

- 查看设置后的看板信息
 - 属性showWIP @1
 - 属性fluidBoard @0
 - 属性colWidth @400
 - 属性archived @1
 - 属性performable @1
 - 属性alignment @center
 - 属性object @plans,releases
- 查看设置后的看板信息
 - 属性showWIP @0
 - 属性fluidBoard @1
 - 属性colWidth @264
 - 属性archived @0
 - 属性performable @0
 - 属性alignment @left
 - 属性object @~~

*/

$kanban1 = new stdclass();
$kanban1->showWIP     = '1';
$kanban1->fluidBoard  = '0';
$kanban1->colWidth    = '400';
$kanban1->archived    = '1';
$kanban1->minColWidth = '280';
$kanban1->performable = '1';
$kanban1->alignment   = 'center';

$kanban2 = new stdclass();
$kanban2->showWIP     = '0';
$kanban2->fluidBoard  = '1';
$kanban2->minColWidth = '280';
$kanban2->maxColWidth = '400';
$kanban2->archived    = '0';
$kanban2->performable = '0';
$kanban2->alignment   = 'left';

$_POST['import']           = 'on';
$_POST['importObjectList'] = array('plans', 'releases');

global $tester;
$tester->loadModel('kanban');
$tester->kanban->setting(1, $kanban1);

$kanban = $tester->kanban->getById(1);
r($kanban) && p('showWIP|fluidBoard|colWidth|archived|performable|alignment|object', '|') && e('1|0|400|1|1|center|plans,releases'); // 查看设置后的看板信息

$_POST['import'] = 'off';
$_POST['importObjectList'] = array();

$tester->kanban->setting(2, $kanban2);
$kanban = $tester->kanban->getById(2);
r($kanban) && p('showWIP|fluidBoard|colWidth|archived|performable|alignment|object', '|') && e('0|1|264|0|0|left|~~'); // 查看设置后的看板信息