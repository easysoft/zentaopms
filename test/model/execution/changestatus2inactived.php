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
$execution->status->range('doing,doing,doing,closed,suspended');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->realBegan->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

/**

title=测试executionModel->changeStatus2Inactived();
cid=1
pid=1

测试修改顶级父阶段执行状态为已挂起 >> '阶段a',
测试修改子阶段执行状态为已关闭 >> empty
测试修改叶子阶段执行状态为已关闭 >> empty

*/

$execution = new executionTest();
r($execution->changeStatus2InactivedObject(2, 'suspended')) && p('') && e("'阶段a',"); // 测试修改顶级父阶段执行状态为已挂起
r($execution->changeStatus2InactivedObject(3, 'closed'))    && p('') && e("empty");    // 测试修改子阶段执行状态为已关闭
r($execution->changeStatus2InactivedObject(4, 'closed'))    && p('') && e('empty');    // 测试修改叶子阶段执行状态为已关闭
