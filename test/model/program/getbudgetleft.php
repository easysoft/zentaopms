#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::getBudgetLeft();
cid=1
pid=1

查看父项目集id=1的预算剩余 >> 0

*/

$parentProjectSet = new Program('admin');

$t_itemsid = array('1');

r($parentProjectSet->getBudgetLeft($t_itemsid[0])) && p() && e('0');  // 查看父项目集id=1的预算剩余