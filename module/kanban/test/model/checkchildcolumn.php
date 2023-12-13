#!/usr/bin/env php
<?php

/**

title=测试 kanbanModel->checkChildColumn();
timeout=0
cid=333

- 正常创建无报错 @0
- 超出父列限制提示属性limit @父列的在制品数量不能小于子列在制品数量之和
- 正整数提示属性limit @在制品数量必须是正整数。
- 名称为空提示属性name @『看板列名称』不能为空。

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$parentColA = new stdclass();
$parentColA->name  = '看板父列A';
$parentColA->limit = 2;

$parentColB = new stdclass();
$parentColB->name  = '看板父列B';
$parentColB->limit = -1;

$columnA = new stdclass();
$columnA->name    = '测试创建子列A';
$columnA->limit   = 1;
$columnA->noLimit = 0;
$columnA->color   = '#333';

$columnB = new stdclass();
$columnB->name    = '测试创建子列B';
$columnB->limit   = 3;
$columnB->noLimit = 0;
$columnB->color   = '#333';

$columnC = new stdclass();
$columnC->name    = '测试创建子列C';
$columnC->limit   = -2;
$columnC->noLimit = 0;
$columnC->color   = '#333';

$columnD = new stdclass();
$columnD->name    = '';
$columnD->limit   = -1;
$columnD->noLimit = '0';
$columnD->color   = '#333';

global $tester;
$tester->loadModel('kanban');

$tester->kanban->checkChildColumn($parentColA, $columnA, 0);
r(dao::getError()) && p('') && e('0'); // 正常创建无报错
$tester->kanban->checkChildColumn($parentColA, $columnB, 4);
r(dao::getError()) && p('limit') && e('父列的在制品数量不能小于子列在制品数量之和'); // 超出父列限制提示
$tester->kanban->checkChildColumn($parentColB, $columnC, 2);
r(dao::getError()) && p('limit') && e('在制品数量必须是正整数。'); // 正整数提示
$tester->kanban->checkChildColumn($parentColB, $columnD, 3);
r(dao::getError()) && p('name') && e('『看板列名称』不能为空。'); // 名称为空提示