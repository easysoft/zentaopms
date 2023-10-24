#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->logHistory();
cid=1
pid=1

测试新增actionID 10001 历史记录 >> name,变更前名称,变更后名称;code,变更前编号,变更后编号
测试新增actionID 10002 历史记录 >> assignedTo,admin,test1
测试新增actionID 10003 历史记录 >> name,name1,name2
测试新增actionID 10004 历史记录 >> code,code1,code2
测试新增actionID 10005 历史记录 >> assignedTo,test2,test1

*/

$actionIDList = array('10001', '10002', '10003', '10004', '10005');

$changes1[0] = array('field' => 'name', 'old' => '变更前名称', 'new' => '变更后名称');
$changes1[1] = array('field' => 'code', 'old' => '变更前编号', 'new' => '变更后编号');
$changes2[0] = array('field' => 'assignedTo', 'old' => 'admin', 'new' => 'test1');
$changes3[0] = array('field' => 'name', 'old' => 'name1', 'new' => 'name2');
$changes4[0] = array('field' => 'code', 'old' => 'code1', 'new' => 'code2');
$changes5[0] = array('field' => 'assignedTo', 'old' => 'test2', 'new' => 'test1');

$action = new actionTest();

r($action->logHistoryTest($actionIDList[0], $changes1)) && p('0:field,old,new;1:field,old,new') && e('name,变更前名称,变更后名称;code,变更前编号,变更后编号'); // 测试新增actionID 10001 历史记录
r($action->logHistoryTest($actionIDList[1], $changes2)) && p('0:field,old,new')                 && e('assignedTo,admin,test1');                                // 测试新增actionID 10002 历史记录
r($action->logHistoryTest($actionIDList[2], $changes3)) && p('0:field,old,new')                 && e('name,name1,name2');                                      // 测试新增actionID 10003 历史记录
r($action->logHistoryTest($actionIDList[3], $changes4)) && p('0:field,old,new')                 && e('code,code1,code2');                                      // 测试新增actionID 10004 历史记录
r($action->logHistoryTest($actionIDList[4], $changes5)) && p('0:field,old,new')                 && e('assignedTo,test2,test1');                                // 测试新增actionID 10005 历史记录
