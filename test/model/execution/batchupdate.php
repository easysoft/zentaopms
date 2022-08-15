#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
$db->switchDB();
su('admin');

/**

title=测试executionModel->batchUpdate();
cid=1
pid=1

测试批量修改任务 >> name,迭代1,批量修改执行一
测试name为空 >> 『name』不能为空。

*/

$executionID = '101';
$name        = array($executionID => '批量修改执行一');
$code        = array($executionID => '批量修改执行一code');
$emptycode   = array($executionID => '');
$pms         = array($executionID => 'outside100');
$lifetimes   = array($executionID => 'short');
$statuses    = array($executionID => 'doing');
$dayses      = array($executionID => '5');

$normal  = array('names' => $name, 'statuses'=> $statuses, 'codes' => $code, 'PMs' => $pms, 'lifetimes' => $lifetimes, 'dayses' => $dayses);
$noName  = array('statuses'=> $statuses, 'codes' => $code, 'PMs' => $pms, 'lifetimes' => $lifetimes, 'dayses' => $dayses);

$execution = new executionTest();
r($execution->batchUpdateObject($normal, $executionID))  && p('0:field,old,new') && e('name,迭代1,批量修改执行一'); // 测试批量修改任务
r($execution->batchUpdateObject($noName, $executionID))  && p()                  && e('『name』不能为空。');        // 测试name为空
$db->restoreDB();