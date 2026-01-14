#!/usr/bin/env php
<?php

/**

title=测试 repoModel::saveRecord();
timeout=0
cid=18099

- 执行repoTest模块的saveRecordTest方法，参数是$action, $log, $repoRoot, $scm
 - 属性action @gitcommited
 - 属性extra @61e51cadb1
- 执行repoTest模块的saveRecordTest方法，参数是$action, $log, $repoRoot, $scm 属性actor @admin
- 执行repoTest模块的saveRecordTest方法，参数是$action, $log, $repoRoot, $scm, true 属性field @git
- 执行repoTest模块的saveRecordTest方法，参数是$emptyAction, $emptyLog, $repoRoot, $scm
 - 属性action @gitcommited
 - 属性objectID @2
- 执行repoTest模块的saveRecordTest方法，参数是$duplicateAction, $log, $repoRoot, $scm
 - 属性action @gitcommited
 - 属性objectID @1
- 执行repoTest模块的saveRecordTest方法，参数是$bugAction, $log, $repoRoot, $scm
 - 属性objectType @bug
 - 属性objectID @1
- 执行repoTest模块的saveRecordTest方法，参数是$commentAction, $commentLog, $repoRoot, $scm
 - 属性objectID @3
 - 属性comment @Specific comment for matching

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
zenData('task')->gen(10);
zenData('bug')->gen(10);
zenData('action')->gen(0);
zenData('history')->gen(0);
zenData('repo')->loadYaml('repo')->gen(4);

// 用户登录
su('admin');

// 创建测试实例
$repoTest = new repoModelTest();

// 测试数据准备
$taskID   = 1;
$bugID    = 1;
$repoRoot = '/test/repo';
$scm      = 'git';

$log = new stdclass();
$log->revision  = '61e51cadb1aa21ef3d2b51e3f193be3cc19cfef6';
$log->committer = 'testuser';
$log->time      = '2023-12-29 10:44:36';
$log->comment   = 'Fix Task #1 and resolve Bug #1';
$log->author    = 'testuser';
$log->msg       = 'Fix Task #1 and resolve Bug #1';
$log->date      = '2023-12-29 10:44:36';
$log->files     = array('M' => array('/src/test.php', '/README.md'));
$log->change    = array(
    '/src/test.php' => array('action' => 'M', 'kind' => 'file', 'oldPath' => ''),
    '/README.md' => array('action' => 'M', 'kind' => 'file', 'oldPath' => '')
);

// 基础action对象
$action  = new stdclass();
$action->actor      = 'testuser';
$action->date       = '2023-12-29 13:14:36';
$action->extra      = substr($log->revision, 0, 10);
$action->objectType = 'task';
$action->objectID   = $taskID;
$action->action     = 'gitcommited';

// 测试步骤1：保存新的action记录
r($repoTest->saveRecordTest($action, $log, $repoRoot, $scm)) && p('action,extra') && e('gitcommited,61e51cadb1');

// 测试步骤2：更新已存在的action记录
$action->actor = 'admin';
r($repoTest->saveRecordTest($action, $log, $repoRoot, $scm)) && p('actor') && e('admin');

// 测试步骤3：验证历史记录正确保存
r($repoTest->saveRecordTest($action, $log, $repoRoot, $scm, true)) && p('field') && e('git');

// 测试步骤4：测试空changes数组场景
$emptyLog = new stdclass();
$emptyLog->revision  = 'abc123def456';
$emptyLog->committer = 'emptyuser';
$emptyLog->time      = '2023-12-30 10:00:00';
$emptyLog->comment   = 'Empty changes test';
$emptyLog->msg       = 'Empty changes test';
$emptyLog->files     = array();
$emptyLog->change    = array();

$emptyAction = new stdclass();
$emptyAction->actor      = 'emptyuser';
$emptyAction->date       = '2023-12-30 10:00:00';
$emptyAction->extra      = substr($emptyLog->revision, 0, 10);
$emptyAction->objectType = 'task';
$emptyAction->objectID   = 2;
$emptyAction->action     = 'gitcommited';

r($repoTest->saveRecordTest($emptyAction, $emptyLog, $repoRoot, $scm)) && p('action,objectID') && e('gitcommited,2');

// 测试步骤5：测试相同action重复保存
$duplicateAction = clone $action;
$duplicateAction->comment = 'Updated comment for existing action';
r($repoTest->saveRecordTest($duplicateAction, $log, $repoRoot, $scm)) && p('action,objectID') && e('gitcommited,1');

// 测试步骤6：测试不同objectType的action
$bugAction = new stdclass();
$bugAction->actor      = 'bugfixer';
$bugAction->date       = '2023-12-29 15:30:00';
$bugAction->extra      = substr($log->revision, 0, 10);
$bugAction->objectType = 'bug';
$bugAction->objectID   = $bugID;
$bugAction->action     = 'gitcommited';

r($repoTest->saveRecordTest($bugAction, $log, $repoRoot, $scm)) && p('objectType,objectID') && e('bug,1');

// 测试步骤7：测试包含comment的action
$commentAction = new stdclass();
$commentAction->actor      = 'commenter';
$commentAction->date       = '2023-12-29 16:00:00';
$commentAction->extra      = 'def456abc123';
$commentAction->objectType = 'task';
$commentAction->objectID   = 3;
$commentAction->action     = 'gitcommited';
$commentAction->comment    = 'Specific comment for matching';

$commentLog = new stdclass();
$commentLog->revision  = 'def456abc123789';
$commentLog->committer = 'commenter';
$commentLog->time      = '2023-12-29 16:00:00';
$commentLog->comment   = 'Test with specific comment';
$commentLog->msg       = 'Test with specific comment';
$commentLog->files     = array('A' => array('/new_file.php'));
$commentLog->change    = array('/new_file.php' => array('action' => 'A', 'kind' => 'file', 'oldPath' => ''));

r($repoTest->saveRecordTest($commentAction, $commentLog, $repoRoot, $scm)) && p('objectID,comment') && e('3,Specific comment for matching');