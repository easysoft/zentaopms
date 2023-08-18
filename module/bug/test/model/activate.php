#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';

su('admin');

zdTable('bug')->config('bug_activate')->gen(27);
zdTable('build')->config('build_activate')->gen(3);
zdTable('product')->config('product_activate')->gen(1);
zdTable('project')->config('project_activate')->gen(6);
zdTable('kanbancell')->config('kanbancell_activate')->gen(27);
zdTable('kanbancolumn')->config('kanbancolumn_activate')->gen(27);
zdTable('kanbanlane')->config('kanbanlane_activate')->gen(3);
zdTable('kanbanregion')->config('kanbanregion_activate')->gen(1);
zdTable('history')->gen(0);

/**

title=bugModel->activate();
timeout=0
cid=1

*/

global $config;

$_SERVER['HTTP_HOST'] = $config->db->host; // 记日志需要用到 HTTP_HOST

$bug          = new bugTest();
$kanbanParams = array();

r($bug->activateTest(2)) && p('status,activatedCount') && e('active,1'); // 状态是 resolved 的 bug 激活后的状态是 active，激活次数加 1。
r($bug->activateTest(3)) && p('status,activatedCount') && e('active,1'); // 状态是 closed   的 bug 激活后的状态是 active，激活次数加 1。


r($bug->activateTest(5, 1, $kanbanParams, 'build')) && p('bugs', ';') && e('4,6'); // 状态是 resolved 的 bug 激活后版本解决的 bug 中不再包含这个 bug。
r($bug->activateTest(6, 1, $kanbanParams, 'build')) && p('bugs')      && e('4');   // 状态是 closed   的 bug 激活后版本解决的 bug 中不再包含这个 bug。

r($bug->activateTest(8,  0, $kanbanParams, 'action')) && p('0:field,old,new') && e('status,resolved,active'); // 状态是 resolved 的 bug 激活后记录状态改变日志。
r($bug->activateTest(9,  0, $kanbanParams, 'action')) && p('0:field,old,new') && e('status,closed,active');   // 状态是 closed   的 bug 激活后记录状态改变日志。
r($bug->activateTest(11, 0, $kanbanParams, 'action')) && p('1:field,old,new') && e('activatedCount,0,1');     // 状态是 resolved 的 bug 激活后记录激活次数改变日志。
r($bug->activateTest(12, 0, $kanbanParams, 'action')) && p('1:field,old,new') && e('activatedCount,0,1');     // 状态是 closed   的 bug 激活后记录激活次数改变日志。

r($bug->activateTest(14, 0, $kanbanParams, 'kanban')) && p() && e('fixing'); // 状态是 resolved 的 bug 激活后更新研发看板。
r($bug->activateTest(15, 0, $kanbanParams, 'kanban')) && p() && e('fixing'); // 状态是 closed   的 bug 激活后更新研发看板。

$kanbanParams['fromColID']  = 23;
$kanbanParams['toColID']    = 22;
$kanbanParams['fromLaneID'] = 3;
$kanbanParams['toLaneID']   = 3;
$kanbanParams['regionID']   = 1;
r($bug->activateTest(20, 0, $kanbanParams, 'kanban')) && p() && e('fixing'); // 状态是 resolved 的 bug 激活后更新看板执行。

$kanbanParams['fromColID'] = 27;
r($bug->activateTest(21, 0, $kanbanParams, 'kanban')) && p() && e('fixing'); // 状态是 closed   的 bug 激活后更新看板执行。
