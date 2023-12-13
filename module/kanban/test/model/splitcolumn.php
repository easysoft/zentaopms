#!/usr/bin/env php
<?php

/**
title=测试 kanbanModel->splitColumn();
timeout=0
cid=333

- 正常创建子看板列，查看创建的数量 @3
- 名字为空的看板列不会创建,返回一个错误 @1
- 查看创建的子看板列的信息
 - 第2条的name属性 @测试创建子列C
 - 第2条的parent属性 @1
 - 第2条的limit属性 @2
- 名字为空给出错误属性name @『看板列名称』不能为空。

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancolumn')->gen(2);

$columnA = new stdclass();
$columnA->name    = '测试创建子列A';
$columnA->limit   = '-1';
$columnA->noLimit = '-1';
$columnA->color   = '#333';

$columnB = new stdclass();
$columnB->name    = '测试创建子列B';
$columnB->limit   = '2';
$columnB->noLimit = '0';
$columnB->color   = '#333';

$columnC = new stdclass();
$columnC->name    = '测试创建子列C';
$columnC->limit   = '2';
$columnC->noLimit = '0';
$columnC->color   = '#333';

$columnD = new stdclass();
$columnD->name    = '';
$columnD->limit   = '2';
$columnD->noLimit = '0';
$columnD->color   = '#333';

$columnE = new stdclass();
$columnE->name    = '测试创建子列E';
$columnE->limit   = '2';
$columnE->noLimit = '0';
$columnE->color   = '#333';

$columnF = new stdclass();
$columnF->name    = '测试创建子列E';
$columnF->limit   = '2';
$columnF->noLimit = '0';
$columnF->color   = '#333';

$childrenA = array($columnA, $columnB, $columnC);
$childrenB = array($columnD, $columnE, $columnF);
$kanban = new kanbanTest();

$childrenA = $kanban->splitColumnTest(1, $childrenA);
$childrenB = $kanban->splitColumnTest(2, $childrenB);

r(count($childrenA)) && p('') && e('3'); // 正常创建子看板列，查看创建的数量
r(count($childrenB)) && p('') && e('1'); // 名字为空的看板列不会创建,返回一个错误

r($childrenA) && p('2:name,parent,limit') && e('测试创建子列C,1,2');        // 查看创建的子看板列的信息
r($childrenB) && p('name')                && e('『看板列名称』不能为空。'); // 名字为空给出错误
