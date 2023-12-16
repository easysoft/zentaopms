#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanban')->gen('25');
zdTable('kanbancard')->gen('1000');
zdTable('productplan')->gen('100');
zdTable('release')->gen('100');
zdTable('build')->gen('100');
zdTable('project')->config('execution')->gen('300');

/**

title=测试 kanbanModel->getImportedCards();
timeout=0
cid=1

- 查看看板1的普通卡片和productplan卡片 @,1,2,3,4,5,6,7,8,801,802,803,804,805,806,807,808
- 查看看板1的普通卡片和release卡片 @,1,2,3,4,5,6,7,8
- 查看看板2的普通卡片和productplan卡片 @,9,10,11,12,13,14,15,16,809,810,811,812,813,814,815,816
- 查看看板2的普通卡片和release卡片 @,9,10,11,12,13,14,15,16
- 查看看板9的普通卡片和release卡片 @,65,66,67,68,69,70,71,72,871,872
- 查看看板9的普通卡片和execution卡片 @,65,66,67,68,69,70,71,72
- 查看看板10的普通卡片和release卡片 @,73,74,75,76,77,78,79,80,873,874,875,876,877,878,879,880
- 查看看板10的普通卡片和execution卡片 @,73,74,75,76,77,78,79,80
- 查看看板11的普通卡片和execution卡片 @,81,82,83,84,85,86,87,88,881,882,883,884,885,886,887,888
- 查看看板11的普通卡片和build卡片 @,81,82,83,84,85,86,87,88
- 查看看板12的普通卡片和execution卡片 @,89,90,91,92,93,94,95,96,889,890,891,892,893,894,895,896
- 查看看板12的普通卡片和build卡片 @,89,90,91,92,93,94,95,96
- 查看看板23的普通卡片和build卡片 @,177,178,179,180,181,182,183,184,981,982,983,984
- 查看看板23的普通卡片和productplan片 @,177,178,179,180,181,182,183,184
- 查看看板25的普通卡片和build卡片 @,193,194,195,196,197,198,199,200,993,994,995,996,997,998,999,1000
- 查看看板25的普通卡片和productplan片 @,193,194,195,196,197,198,199,200
- 查看看板1已归档的普通卡片和productplan卡片 @,6,7,8,801,802,803,804,805,806,807,808
- 查看区域ID=1中看板1已归档的普通卡片和productplan卡片 @,6,7,8,801,802,803,804,805,806,807,808

*/

$kanbanIDList = array('1', '2', '9', '10', '11', '12', '23', '25');
$fromType     = array('productplan', 'release', 'execution', 'build');
$archived     = 1;

$kanban = new kanbanTest();

r($kanban->getImportedCardsTest($kanbanIDList[0], $fromType[0])) && p('', '|') && e(',1,2,3,4,5,6,7,8,801,802,803,804,805,806,807,808');                  // 查看看板1的普通卡片和productplan卡片
r($kanban->getImportedCardsTest($kanbanIDList[0], $fromType[1])) && p('', '|') && e(',1,2,3,4,5,6,7,8');                                                  // 查看看板1的普通卡片和release卡片
r($kanban->getImportedCardsTest($kanbanIDList[1], $fromType[0])) && p('', '|') && e(',9,10,11,12,13,14,15,16,809,810,811,812,813,814,815,816');           // 查看看板2的普通卡片和productplan卡片
r($kanban->getImportedCardsTest($kanbanIDList[1], $fromType[1])) && p('', '|') && e(',9,10,11,12,13,14,15,16');                                           // 查看看板2的普通卡片和release卡片
r($kanban->getImportedCardstest($kanbanIDList[2], $fromType[1])) && p('', '|') && e(',65,66,67,68,69,70,71,72,871,872');                                  // 查看看板9的普通卡片和release卡片
r($kanban->getImportedCardstest($kanbanIDList[2], $fromType[2])) && p('', '|') && e(',65,66,67,68,69,70,71,72');                                          // 查看看板9的普通卡片和execution卡片
r($kanban->getImportedCardstest($kanbanIDList[3], $fromType[1])) && p('', '|') && e(',73,74,75,76,77,78,79,80,873,874,875,876,877,878,879,880');          // 查看看板10的普通卡片和release卡片
r($kanban->getImportedCardstest($kanbanIDList[3], $fromType[2])) && p('', '|') && e(',73,74,75,76,77,78,79,80');                                          // 查看看板10的普通卡片和execution卡片
r($kanban->getImportedCardstest($kanbanIDList[4], $fromType[2])) && p('', '|') && e(',81,82,83,84,85,86,87,88,881,882,883,884,885,886,887,888');          // 查看看板11的普通卡片和execution卡片
r($kanban->getImportedCardstest($kanbanIDList[4], $fromType[3])) && p('', '|') && e(',81,82,83,84,85,86,87,88');                                          // 查看看板11的普通卡片和build卡片
r($kanban->getImportedCardstest($kanbanIDList[5], $fromType[2])) && p('', '|') && e(',89,90,91,92,93,94,95,96,889,890,891,892,893,894,895,896');          // 查看看板12的普通卡片和execution卡片
r($kanban->getImportedCardstest($kanbanIDList[5], $fromType[3])) && p('', '|') && e(',89,90,91,92,93,94,95,96');                                          // 查看看板12的普通卡片和build卡片
r($kanban->getImportedCardstest($kanbanIDList[6], $fromType[3])) && p('', '|') && e(',177,178,179,180,181,182,183,184,981,982,983,984');                  // 查看看板23的普通卡片和build卡片
r($kanban->getImportedCardstest($kanbanIDList[6], $fromType[0])) && p('', '|') && e(',177,178,179,180,181,182,183,184');                                  // 查看看板23的普通卡片和productplan片
r($kanban->getImportedCardstest($kanbanIDList[7], $fromType[3])) && p('', '|') && e(',193,194,195,196,197,198,199,200,993,994,995,996,997,998,999,1000'); // 查看看板25的普通卡片和build卡片
r($kanban->getImportedCardstest($kanbanIDList[7], $fromType[0])) && p('', '|') && e(',193,194,195,196,197,198,199,200');                                  // 查看看板25的普通卡片和productplan片
global $tester;
$tester->dao->update(TABLE_KANBANCARD)->set('archived')->eq(1)->where('id')->gt('5')->exec();
r($kanban->getImportedCardsTest($kanbanIDList[0], $fromType[0], $archived))    && p('', '|') && e(',6,7,8,801,802,803,804,805,806,807,808');              // 查看看板1已归档的普通卡片和productplan卡片
r($kanban->getImportedCardsTest($kanbanIDList[0], $fromType[0], $archived, 1)) && p('', '|') && e(',6,7,8,801,802,803,804,805,806,807,808');              // 查看区域ID=1中看板1已归档的普通卡片和productplan卡片