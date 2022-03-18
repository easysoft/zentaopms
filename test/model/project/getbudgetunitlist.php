#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

/**

title=测试 projectModel::getBudgetUnitList();
cid=1
pid=1

检查翻译 >> 1

*/

$t = new Project('admin');
/* GetBudgetUnitList(). */
r($t->checkBudgetUnitList()) && p() && e('1'); //检查翻译