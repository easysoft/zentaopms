#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 commonModel::saveQueryCondition();
timeout=0
cid=1

- 仅保留WHERE条件 @id = 1
- 保留完整SQL语句 @SELECT * FROM zt_execution WHERE id = 1

*/

global $tester;

$tester->loadModel('common')->saveQueryCondition('SELECT * FROM zt_task WHERE id = 1', 'task', true);
$tester->loadModel('common')->saveQueryCondition('SELECT * FROM zt_execution WHERE id = 1', 'execution', false);

r($tester->session->taskQueryCondition)      && p('') && e('id = 1'); // 仅保留WHERE条件
r($tester->session->executionQueryCondition) && p('') && e('SELECT * FROM zt_execution WHERE id = 1'); //  保留完整SQL语句