#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::createStakeholder();
cid=1
pid=1

创建id=1的项目集的干系人dev1,dev2并查看。 >> dev2;dev1

*/
$Stakeholder = new Program('admin');

$t_Stakeholder = array('1');

r($Stakeholder->createStakeholder($t_Stakeholder[0])) && p('0:account;1:account') && e('dev2;dev1'); // 创建id=1的项目集的干系人dev1,dev2并查看。