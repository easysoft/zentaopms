#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

zenData('bug')->loadYaml('bug_activate')->gen(27);
zenData('build')->loadYaml('build_activate')->gen(3);
zenData('product')->loadYaml('product_activate')->gen(1);
zenData('project')->loadYaml('project_activate')->gen(6);
zenData('kanbancell')->loadYaml('kanbancell_activate')->gen(27);
zenData('kanbancolumn')->loadYaml('kanbancolumn_activate')->gen(27);
zenData('kanbanlane')->loadYaml('kanbanlane_activate')->gen(3);
zenData('kanbanregion')->loadYaml('kanbanregion_activate')->gen(1);
zenData('history')->gen(0);

/**

title=bugModel->activate();
timeout=0
cid=15344

- 状态是 resolved 的 bug 激活后的状态是 active，激活次数加 1。
 - 属性status @active
 - 属性activatedCount @1
- 状态是 closed   的 bug 激活后的状态是 active，激活次数加 1。
 - 属性status @active
 - 属性activatedCount @1
- 状态是 resolved 的 bug 激活后版本解决的 bug 中不再包含这个 bug。属性bugs @4,6
- 状态是 closed   的 bug 激活后版本解决的 bug 中不再包含这个 bug。属性bugs @4
- 状态是 resolved 的 bug 激活后记录状态改变日志。第0条的field属性 @status
- 状态是 closed   的 bug 激活后记录状态改变日志。第0条的field属性 @status
- 状态是 resolved 的 bug 激活后记录激活次数改变日志。第1条的field属性 @activatedCount
- 状态是 closed   的 bug 激活后记录激活次数改变日志。第1条的field属性 @activatedCount
- 状态是 resolved 的 bug 激活后更新研发看板。 @fixing
- 状态是 closed   的 bug 激活后更新研发看板。 @fixing
- 状态是 resolved 的 bug 激活后更新看板执行。 @fixing
- 状态是 closed   的 bug 激活后更新看板执行。 @fixing

*/

global $config;

$_SERVER['HTTP_HOST'] = $config->db->host; // 记日志需要用到 HTTP_HOST

$bug          = new bugModelTest();
$kanbanParams = array();

r($bug->activateTest(2)) && p('status,activatedCount') && e('active,1'); // 状态是 resolved 的 bug 激活后的状态是 active，激活次数加 1。
r($bug->activateTest(3)) && p('status,activatedCount') && e('active,1'); // 状态是 closed   的 bug 激活后的状态是 active，激活次数加 1。


r($bug->activateTest(5, 1, $kanbanParams, 'build')) && p('bugs', ';') && e('4,6'); // 状态是 resolved 的 bug 激活后版本解决的 bug 中不再包含这个 bug。
r($bug->activateTest(6, 1, $kanbanParams, 'build')) && p('bugs')      && e('4');   // 状态是 closed   的 bug 激活后版本解决的 bug 中不再包含这个 bug。

r($bug->activateTest(8,  0, $kanbanParams, 'action')) && p('0:field') && e('status');         // 状态是 resolved 的 bug 激活后记录状态改变日志。
r($bug->activateTest(9,  0, $kanbanParams, 'action')) && p('0:field') && e('status');         // 状态是 closed   的 bug 激活后记录状态改变日志。
r($bug->activateTest(11, 0, $kanbanParams, 'action')) && p('1:field') && e('activatedCount'); // 状态是 resolved 的 bug 激活后记录激活次数改变日志。
r($bug->activateTest(12, 0, $kanbanParams, 'action')) && p('1:field') && e('activatedCount'); // 状态是 closed   的 bug 激活后记录激活次数改变日志。

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
