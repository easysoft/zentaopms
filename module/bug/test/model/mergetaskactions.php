#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$action = zenData('action');
$action->objectType->range('bug,bug,task,task,task,bug');
$action->objectID->range('1,1,100,100,100,1');
$action->actor->range('admin');
$action->action->range('edited,converttotask,opened,finished,edited,converttotask');
$action->date->range('`2026-08-01 09:00:00`,`2026-08-01 11:00:00`,`2026-08-01 10:00:00`,`2026-08-01 10:30:00`,`2026-08-01 12:00:00`,`2026-08-01 13:00:00`');
$action->extra->range('``,`100`,``,``,``,``');
$action->comment->range('');
$action->vision->range('rnd');
$action->gen(6);

zenData('history')->gen(0);

su('admin');

/**

title=bugModel->mergeTaskActions();
cid=0

- 合并 Bug 转任务后的动作，并按时间重新排序 @1,4,2,5,6

- 跳过任务 opened 动作 @0
- 转换出的任务动作标记为 feedback 来源
 - 属性action @finished
 - 属性from @feedback
- 转换出的任务动作标记为 feedback 来源
 - 属性action @edited
 - 属性from @feedback

*/

$bug    = new bugModelTest();
$result = $bug->mergeTaskActionsTest(1);

r(implode(',', array_keys($result))) && p() && e('1,4,2,5,6');           // 合并 Bug 转任务后的动作，并按时间重新排序
r(isset($result[3]))                 && p() && e('0');                   // 跳过任务 opened 动作
r($result[4])                        && p('action,from') && e('finished,feedback'); // 转换出的任务动作标记为 feedback 来源
r($result[5])                        && p('action,from') && e('edited,feedback');   // 转换出的任务动作标记为 feedback 来源
