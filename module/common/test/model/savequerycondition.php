#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 commonModel::saveQueryCondition();
timeout=0
cid=15707

- 仅保留WHERE条件 @id = 1
- 保留完整SQL语句 @SELECT * FROM zt_execution WHERE id = 1
- 仅保留WHERE条件 @1=1
- 仅保留WHERE条件 @id = 1 and type = "story"
- 仅保留WHERE条件 @id = 1 and type = "epic"

*/

global $tester;

$tester->loadModel('common')->saveQueryCondition('SELECT * FROM zt_task WHERE id = 1', 'task', true);
$tester->loadModel('common')->saveQueryCondition('SELECT * FROM zt_execution WHERE id = 1', 'execution', false);
$tester->loadModel('common')->saveQueryCondition('', 'testcase', true);
$tester->loadModel('common')->saveQueryCondition('SELECT * FROM zt_story WHERE id = 1 and type = "story"', 'story', true);
$tester->loadModel('common')->saveQueryCondition('SELECT * FROM zt_epic WHERE id = 1 and type = "epic"', 'epic', true);

r($tester->session->taskQueryCondition)      && p('') && e('id = 1'); // 仅保留WHERE条件
r($tester->session->executionQueryCondition) && p('') && e('SELECT * FROM zt_execution WHERE id = 1'); //  保留完整SQL语句
r($tester->session->testcaseQueryCondition)  && p('') && e('1=1'); // 仅保留WHERE条件
r($tester->session->storyQueryCondition)     && p('') && e('id = 1 and type = "story"'); // 仅保留WHERE条件
r($tester->session->epicQueryCondition)      && p('') && e('id = 1 and type = "epic"'); // 仅保留WHERE条件