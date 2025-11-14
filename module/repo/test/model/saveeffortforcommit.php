#!/usr/bin/env php
<?php

/**

title=测试 repoModel::saveEffortForCommit();
timeout=0
cid=18095

- 执行$result1
 - 属性status @doing
 - 属性consumed @11
 - 属性left @3
- 执行$result2
 - 属性status @doing
 - 属性consumed @12
 - 属性left @0
- 执行$result3
 - 属性status @doing
 - 属性consumed @10
 - 属性left @5
- 执行$result4
 - 属性status @doing
 - 属性consumed @15
 - 属性left @1
- 执行$result5
 - 属性status @doing
 - 属性consumed @13
 - 属性left @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';
su('admin');

zenData('task')->gen(10);
$bug = zenData('bug');
$bug->execution->range('0');
$bug->gen(10);
zenData('repo')->loadYaml('repo')->gen(4);

$repoID   = 1;
$repoRoot = '';
$scm      = 'gitlab';

global $app;
include($app->getModuleRoot() . '/repo/control.php');
$app->control = new repo();

$repo = new repoTest();

// 测试步骤1：基础工时记录(消耗1小时，剩余3小时)
$log1 = new stdclass();
$log1->revision  = '61e51cadb1aa21ef3d2b51e3f193be3cc19cfef6';
$log1->committer = 'root';
$log1->time      = '2023-12-29 10:44:36';
$log1->comment   = 'Effort Task #8 Cost:1h Left:3h';
$log1->author    = 'user4';
$log1->msg       = 'Effort Task #8 Cost:1h Left:3h';
$log1->date      = '2023-12-29 10:44:36';
$log1->files     = array('M' => array('/README.md'));
$log1->change    = array('/README.md' => array('action' => 'M', 'kind' => 'file', 'oldPath' => ''));

$action1  = new stdclass();
$action1->actor  = 'user4';
$action1->date   = '2023-12-29 13:14:36';
$action1->extra  = $scm == 'svn' ? $log1->revision : substr($log1->revision, 0, 10);
$action1->action = 'gitcommited';

$repo->saveEffortForCommitTest($log1, $action1, $repoID);
$result1 = $tester->loadModel('task')->getById(8);
r($result1) && p('status,consumed,left') && e('doing,11,3');

// 测试步骤2：零剩余时间(消耗2小时，剩余0小时)
$log2 = new stdclass();
$log2->revision  = '71e51cadb1aa21ef3d2b51e3f193be3cc19cfef7';
$log2->committer = 'admin';
$log2->time      = '2023-12-29 11:00:00';
$log2->comment   = 'Effort Task #9 Cost:2h Left:0h';
$log2->author    = 'admin';
$log2->msg       = 'Effort Task #9 Cost:2h Left:0h';
$log2->date      = '2023-12-29 11:00:00';
$log2->files     = array('M' => array('/src/main.php'));
$log2->change    = array('/src/main.php' => array('action' => 'M', 'kind' => 'file', 'oldPath' => ''));

$action2  = new stdclass();
$action2->actor  = 'admin';
$action2->date   = '2023-12-29 14:00:00';
$action2->extra  = $scm == 'svn' ? $log2->revision : substr($log2->revision, 0, 10);
$action2->action = 'gitcommited';

$repo->saveEffortForCommitTest($log2, $action2, $repoID);
$result2 = $tester->loadModel('task')->getById(9);
r($result2) && p('status,consumed,left') && e('doing,12,0');

// 测试步骤3：边界值测试(消耗0小时，剩余5小时)
$log3 = new stdclass();
$log3->revision  = '81e51cadb1aa21ef3d2b51e3f193be3cc19cfef8';
$log3->committer = 'user1';
$log3->time      = '2023-12-29 12:00:00';
$log3->comment   = 'Effort Task #10 Cost:0h Left:5h';
$log3->author    = 'user1';
$log3->msg       = 'Effort Task #10 Cost:0h Left:5h';
$log3->date      = '2023-12-29 12:00:00';
$log3->files     = array('A' => array('/docs/README.txt'));
$log3->change    = array('/docs/README.txt' => array('action' => 'A', 'kind' => 'file', 'oldPath' => ''));

$action3  = new stdclass();
$action3->actor  = 'user1';
$action3->date   = '2023-12-29 15:00:00';
$action3->extra  = $scm == 'svn' ? $log3->revision : substr($log3->revision, 0, 10);
$action3->action = 'gitcommited';

$repo->saveEffortForCommitTest($log3, $action3, $repoID);
$result3 = $tester->loadModel('task')->getById(10);
r($result3) && p('status,consumed,left') && e('doing,10,5');

// 测试步骤4：高工时记录(消耗5小时，剩余1小时)
$log4 = new stdclass();
$log4->revision  = '91e51cadb1aa21ef3d2b51e3f193be3cc19cfef9';
$log4->committer = 'user2';
$log4->time      = '2023-12-29 13:00:00';
$log4->comment   = 'Effort Task #1 Cost:5h Left:1h';
$log4->author    = 'user2';
$log4->msg       = 'Effort Task #1 Cost:5h Left:1h';
$log4->date      = '2023-12-29 13:00:00';
$log4->files     = array('M' => array('/lib/common.php'));
$log4->change    = array('/lib/common.php' => array('action' => 'M', 'kind' => 'file', 'oldPath' => ''));

$action4  = new stdclass();
$action4->actor  = 'user2';
$action4->date   = '2023-12-29 16:00:00';
$action4->extra  = $scm == 'svn' ? $log4->revision : substr($log4->revision, 0, 10);
$action4->action = 'gitcommited';

$repo->saveEffortForCommitTest($log4, $action4, $repoID);
$result4 = $tester->loadModel('task')->getById(1);
r($result4) && p('status,consumed,left') && e('doing,15,1');

// 测试步骤5：完整工时记录(消耗3小时，剩余0小时)
$log5 = new stdclass();
$log5->revision  = 'a1e51cadb1aa21ef3d2b51e3f193be3cc19cfefa';
$log5->committer = 'user3';
$log5->time      = '2023-12-29 14:00:00';
$log5->comment   = 'Effort Task #2 Cost:3h Left:0h';
$log5->author    = 'user3';
$log5->msg       = 'Effort Task #2 Cost:3h Left:0h';
$log5->date      = '2023-12-29 14:00:00';
$log5->files     = array('M' => array('/config/config.php'));
$log5->change    = array('/config/config.php' => array('action' => 'M', 'kind' => 'file', 'oldPath' => ''));

$action5  = new stdclass();
$action5->actor  = 'user3';
$action5->date   = '2023-12-29 17:00:00';
$action5->extra  = $scm == 'svn' ? $log5->revision : substr($log5->revision, 0, 10);
$action5->action = 'gitcommited';

$repo->saveEffortForCommitTest($log5, $action5, $repoID);
$result5 = $tester->loadModel('task')->getById(2);
r($result5) && p('status,consumed,left') && e('doing,13,0');