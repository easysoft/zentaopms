#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::getBudgetUnitList();
cid=1
pid=1

获取货币类型列表 >> 人民币;美元

*/

$CurrencyType = new Program('admin');

/* GetBudgetUnitList(). */
r($CurrencyType->getBudgetUnitList()) && p('CNY;USD') && e('人民币;美元'); //获取货币类型列表