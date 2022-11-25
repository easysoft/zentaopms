#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';
$db->switchDB();

/**

title=测试 programModel::createStakeholder();
cid=1
pid=1

创建id=1的项目集的干系人并查看数量。 >> 2
创建id=1的项目集的干系人dev1,dev2并查看Account。 >> dev2;dev1

*/

global $tester;
$tester->loadModel('program');

$_POST['accounts'] = array('dev1', 'dev2');
$tester->program->createStakeholder(1);
$result = $tester->program->getStakeholdersByPrograms(1);

r(count($result)) && p('')                    && e('2');         // 创建id=1的项目集的干系人并查看数量。
r($result)        && p('0:account;1:account') && e('dev2;dev1'); // 创建id=1的项目集的干系人dev1,dev2并查看Account。
$db->restoreDB();