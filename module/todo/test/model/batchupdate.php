#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');
function initData()
{
    zenData('todo')->loadYaml('batchupdate')->gen(5);
}

/**

title=测试 todoModel->batchUpdate();
timeout=0
cid=19249

- 批量修改todo类型
 - 第0条的field属性 @type
 - 第0条的old属性 @custom
 - 第0条的new属性 @bug
- 批量修改todo优先级
 - 第0条的field属性 @pri
 - 第0条的old属性 @3
 - 第0条的new属性 @1
- 批量修改todo状态
 - 第0条的field属性 @status
 - 第0条的old属性 @wait
 - 第0条的new属性 @doing

*/

initData();

$changeType = array();
$changeType[1]           = new stdclass();
$changeType[1]->type     = 'bug';
$changeType[1]->status   = 'wait';
$changeType[1]->objectID = 1;

$changePri = array();
$changePri[2]         = new stdclass();
$changePri[2]->pri    = '1';
$changePri[2]->status = 'wait';
$changePri[2]->type   = 'custom';

$changeStatus = array();
$changeStatus[3]         = new stdclass();
$changeStatus[3]->status = 'doing';
$changeStatus[3]->type   = 'custom';

$todo = new todoModelTest();
r($todo->batchUpdateTest($changeType, 1))   && p('0:field,old,new')  && e('type,custom,bug');   // 批量修改todo类型
r($todo->batchUpdateTest($changePri, 2))    && p('0:field,old,new')  && e('pri,3,1');           // 批量修改todo优先级
r($todo->batchUpdateTest($changeStatus, 3)) && p('0:field,old,new')  && e('status,wait,doing'); // 批量修改todo状态