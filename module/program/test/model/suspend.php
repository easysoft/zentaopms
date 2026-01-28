#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

// 直接插入测试数据，避免zendata问题
global $tester;
$tester->dao->delete()->from(TABLE_PROJECT)->where('type')->eq('program')->exec();
$tester->dao->insert(TABLE_PROJECT)->data(array(
    'id' => 1,
    'name' => '等待状态项目集',
    'type' => 'program',
    'status' => 'wait',
    'openedBy' => 'admin',
    'openedDate' => '2023-01-01 00:00:00',
    'PM' => 'admin',
    'path' => ',1,',
    'grade' => 1,
    'parent' => 0,
    'deleted' => 0,
    'begin' => '2023-01-01',
    'end' => '2023-12-31'
))->exec();
$tester->dao->insert(TABLE_PROJECT)->data(array(
    'id' => 2,
    'name' => '进行中状态项目集',
    'type' => 'program',
    'status' => 'doing',
    'openedBy' => 'admin',
    'openedDate' => '2023-01-01 00:00:00',
    'PM' => 'admin',
    'path' => ',2,',
    'grade' => 1,
    'parent' => 0,
    'deleted' => 0,
    'begin' => '2023-01-01',
    'end' => '2023-12-31'
))->exec();
$tester->dao->insert(TABLE_PROJECT)->data(array(
    'id' => 3,
    'name' => '已挂起状态项目集',
    'type' => 'program',
    'status' => 'suspended',
    'openedBy' => 'admin',
    'openedDate' => '2023-01-01 00:00:00',
    'PM' => 'admin',
    'path' => ',3,',
    'grade' => 1,
    'parent' => 0,
    'deleted' => 0,
    'begin' => '2023-01-01',
    'end' => '2023-12-31'
))->exec();

/**

title=测试 programModel::suspend();
timeout=0
cid=17711

- 步骤1：正常挂起等待状态的项目集 @1
- 步骤2：正常挂起进行中状态的项目集 @1
- 步骤3：挂起不存在的项目集 @0
- 步骤4：使用空评论挂起项目集 @1
- 步骤5：带评论挂起项目集 @1

*/

$programTest = new programModelTest();

r($programTest->suspendTest(1, array('comment' => '测试挂起', 'uid' => ''))) && p() && e('1'); // 步骤1：正常挂起等待状态的项目集
r($programTest->suspendTest(2, array('comment' => '挂起进行中项目集', 'uid' => ''))) && p() && e('1'); // 步骤2：正常挂起进行中状态的项目集
r($programTest->suspendTest(999, array('comment' => '不存在项目集', 'uid' => ''))) && p() && e('0'); // 步骤3：挂起不存在的项目集
r($programTest->suspendTest(3, array('comment' => '', 'uid' => ''))) && p() && e('1'); // 步骤4：使用空评论挂起项目集
r($programTest->suspendTest(1, array('comment' => '带评论的挂起操作', 'uid' => ''))) && p() && e('1'); // 步骤5：带评论挂起项目集