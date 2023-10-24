#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('瀑布项目1,阶段a,阶段a子1,阶段a子1子1,阶段b');
$execution->type->range('project,stage{4}');
$execution->project->range('0,1{4}');
$execution->parent->range('0,1,2,3,1');
$execution->path->range("`,1,`,`,1,2,`,`,1,2,3,`,`,1,2,3,4,`,`,1,5,`");
$execution->status->range('wait{5}');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

/**

title=测试executionModel->batchChangeStatus();
cid=1
pid=1

测试批量修改执行状态为进行中 >> empty
测试批量修改执行状态为未开始 >> empty
测试批量修改执行状态为已挂起 >> empty
测试批量修改执行状态为已关闭 >> 阶段a子1

*/

$execution = new executionTest();
r($execution->batchChangeStatusObject(array(4), 'doing'))     && p('') && e('empty');    // 测试批量修改执行状态为进行中
r($execution->batchChangeStatusObject(array(2), 'wait'))      && p('') && e('empty');    // 测试批量修改执行状态为未开始
r($execution->batchChangeStatusObject(array(5), 'suspended')) && p('') && e('empty');    // 测试批量修改执行状态为已挂起
r($execution->batchChangeStatusObject(array(3), 'closed'))    && p('') && e('阶段a子1'); // 测试批量修改执行状态为已关闭
