#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->import();
cid=1
pid=1

开启看板1的导入功能 可选项全部 >> plans,releases,builds,executions,cards 
开启看板1的导入功能 可选项plans cards >> plans,cards
关闭看板1的导入功能 可选项全部 >> 0
关闭看板1的导入功能 可选项plans cards >> 0
开启看板2的导入功能 可选项全部 >> plans,releases,builds,executions,cards 
开启看板2的导入功能 可选项plans cards >> plans,cards
关闭看板2的导入功能 可选项全部 >> 0
关闭看板2的导入功能 可选项plans cards >> 0
开启看板3的导入功能 可选项全部 >> plans,releases,builds,executions,cards 
开启看板3的导入功能 可选项plans cards >> plans,cards
关闭看板3的导入功能 可选项全部 >> 0
关闭看板3的导入功能 可选项plans cards >> 0
开启看板4的导入功能 可选项全部 >> plans,releases,builds,executions,cards 
开启看板4的导入功能 可选项plans cards >> plans,cards
关闭看板4的导入功能 可选项全部 >> 0
关闭看板4的导入功能 可选项plans cards >> 0
开启看板5的导入功能 可选项全部 >> plans,releases,builds,executions,cards 
开启看板5的导入功能 可选项plans cards >> plans,cards
关闭看板5的导入功能 可选项全部 >> 0
关闭看板5的导入功能 可选项plans cards >> 0

*/

$kanban = new kanbanTest();

$kanbanIDList = array('1', '2', '3', '4', '5');
$import = array('on', 'off');
$importObjectList1 = array('plans', 'releases', 'builds', 'executions', 'cards');
$importObjectList2 = array('plans', 'cards');

r($kanban->importTest($kanbanIDList[0], $import[0], $importObjectList1)) && p('object') && e('plans,releases,builds,executions,cards '); // 开启看板1的导入功能 可选项全部
r($kanban->importTest($kanbanIDList[0], $import[0], $importObjectList2)) && p('object') && e('plans,cards');                             // 开启看板1的导入功能 可选项plans cards
r($kanban->importTest($kanbanIDList[0], $import[1], $importObjectList1)) && p('object') && e('0');                                       // 关闭看板1的导入功能 可选项全部
r($kanban->importTest($kanbanIDList[0], $import[1], $importObjectList2)) && p('object') && e('0');                                       // 关闭看板1的导入功能 可选项plans cards
r($kanban->importTest($kanbanIDList[1], $import[0], $importObjectList1)) && p('object') && e('plans,releases,builds,executions,cards '); // 开启看板2的导入功能 可选项全部
r($kanban->importTest($kanbanIDList[1], $import[0], $importObjectList2)) && p('object') && e('plans,cards');                             // 开启看板2的导入功能 可选项plans cards
r($kanban->importTest($kanbanIDList[1], $import[1], $importObjectList1)) && p('object') && e('0');                                       // 关闭看板2的导入功能 可选项全部
r($kanban->importTest($kanbanIDList[1], $import[1], $importObjectList2)) && p('object') && e('0');                                       // 关闭看板2的导入功能 可选项plans cards
r($kanban->importTest($kanbanIDList[2], $import[0], $importObjectList1)) && p('object') && e('plans,releases,builds,executions,cards '); // 开启看板3的导入功能 可选项全部
r($kanban->importTest($kanbanIDList[2], $import[0], $importObjectList2)) && p('object') && e('plans,cards');                             // 开启看板3的导入功能 可选项plans cards
r($kanban->importTest($kanbanIDList[2], $import[1], $importObjectList1)) && p('object') && e('0');                                       // 关闭看板3的导入功能 可选项全部
r($kanban->importTest($kanbanIDList[2], $import[1], $importObjectList2)) && p('object') && e('0');                                       // 关闭看板3的导入功能 可选项plans cards
r($kanban->importTest($kanbanIDList[3], $import[0], $importObjectList1)) && p('object') && e('plans,releases,builds,executions,cards '); // 开启看板4的导入功能 可选项全部
r($kanban->importTest($kanbanIDList[3], $import[0], $importObjectList2)) && p('object') && e('plans,cards');                             // 开启看板4的导入功能 可选项plans cards
r($kanban->importTest($kanbanIDList[3], $import[1], $importObjectList1)) && p('object') && e('0');                                       // 关闭看板4的导入功能 可选项全部
r($kanban->importTest($kanbanIDList[3], $import[1], $importObjectList2)) && p('object') && e('0');                                       // 关闭看板4的导入功能 可选项plans cards
r($kanban->importTest($kanbanIDList[4], $import[0], $importObjectList1)) && p('object') && e('plans,releases,builds,executions,cards '); // 开启看板5的导入功能 可选项全部
r($kanban->importTest($kanbanIDList[4], $import[0], $importObjectList2)) && p('object') && e('plans,cards');                             // 开启看板5的导入功能 可选项plans cards
r($kanban->importTest($kanbanIDList[4], $import[1], $importObjectList1)) && p('object') && e('0');                                       // 关闭看板5的导入功能 可选项全部
r($kanban->importTest($kanbanIDList[4], $import[1], $importObjectList2)) && p('object') && e('0');                                       // 关闭看板5的导入功能 可选项plans cards
