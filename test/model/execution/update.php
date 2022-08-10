#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
$db->switchDB();
su('admin');

/**

title=测试executionModel->update();
cid=1
pid=1

测试重复迭代code >> 『迭代代号』已经有『project1』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。
测试修改迭代名称 >> name,迭代1,迭代名修改
测试修改迭代项目为瀑布项目 >> project,11,41
测试修改迭代项目为看板项目 >> project,41,71
测试修改迭代code >> code,project31,code修改
测试修改迭代工作日 >> days,0,5
测试修改迭代类型 >> lifetime,,long
测试修改迭代状态为wait >> status,wait,doing
测试修改迭代状态为closed >> status,doing,closed
测试修改名称为空 >> 『阶段名称』不能为空。
测试修改code为空 >> 『迭代代号』不能为空。
测试无修改 >> 没有数据更新

*/

$executionIDList = array('101','131','251','4','5','6','7','8','9');
$productIDList   = array('2','82','92');

$changeName     = array('name' => '迭代名修改', 'code' => '迭代名修改code');
$changeStage    = array('project' =>'41', 'code' => '修改code');
$changeKanban   = array('project' =>'71', 'code' => '修改code2');
$changeKanban   = array('project' =>'71', 'code' => '修改code3');
$changeCode     = array('code' => 'code修改');
$changeDays     = array('days' => '5');
$changeLifetime = array('lifetime' => 'long');
$changeDoing    = array('status' => 'doing');
$changeClosed   = array('status' => 'closed');
$noChange       = array();
$noName         = array('name' => '');
$noCode         = array('code' => '');
$repeatcode     = array('name' => '迭代名修改1');

$execution = new executionTest();
r($execution->updateObject($executionIDList[0], $repeatcode))     && p('code:0')          && e('『迭代代号』已经有『project1』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。'); // 测试重复迭代code
r($execution->updateObject($executionIDList[0], $changeName))     && p('0:field,old,new') && e('name,迭代1,迭代名修改');      // 测试修改迭代名称
r($execution->updateObject($executionIDList[0], $changeStage))    && p('0:field,old,new') && e('project,11,41');              // 测试修改迭代项目为瀑布项目
r($execution->updateObject($executionIDList[0], $changeKanban))   && p('0:field,old,new') && e('project,41,71');              // 测试修改迭代项目为看板项目
r($execution->updateObject($executionIDList[1], $changeCode))     && p('0:field,old,new') && e('code,project31,code修改');    // 测试修改迭代code
r($execution->updateObject($executionIDList[1], $changeDays))     && p('0:field,old,new') && e('days,0,5');                   // 测试修改迭代工作日
r($execution->updateObject($executionIDList[1], $changeLifetime)) && p('0:field,old,new') && e('lifetime,,long');             // 测试修改迭代类型
r($execution->updateObject($executionIDList[1], $changeDoing))    && p('0:field,old,new') && e('status,wait,doing');          // 测试修改迭代状态为wait
r($execution->updateObject($executionIDList[1], $changeClosed))   && p('0:field,old,new') && e('status,doing,closed');        // 测试修改迭代状态为closed
r($execution->updateObject($executionIDList[1], $noName))         && p('name:0')          && e('『阶段名称』不能为空。');     // 测试修改名称为空
r($execution->updateObject($executionIDList[1], $noCode))         && p('code:0')          && e('『迭代代号』不能为空。');     // 测试修改code为空
r($execution->updateObject($executionIDList[1], $noChange))       && p()                  && e('没有数据更新');               // 测试无修改

$db->restoreDB();