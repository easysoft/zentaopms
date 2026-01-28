#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('lang')->gen(0);
zenData('user')->gen(1);

su('admin');

/**

title=测试 messageModel->getObjectActions();
cid=17053

- 查询objectType为product的action是opened的lab标签第product条的opened属性 @创建
- 查询objectType为story的action是frombug的lab标签第story条的frombug属性 @转研发需求
- 查询objectType为productplan的action是edited的lab标签第productplan条的edited属性 @编辑
- 查询objectType为project的action是delayed的lab标签第project条的delayed属性 @延期
- 查询objectType为task的action是assigned的lab标签第task条的assigned属性 @指派
- 查询objectType为bug的action是closed的lab标签第bug条的closed属性 @关闭
- 查询objectType为case的action是opended的lab标签第case条的opened属性 @创建
- 查询objectType为testtask的action是starteded的lab标签第testtask条的started属性 @开始
- 查询objectType为todo的action是edited的lab标签第todo条的edited属性 @编辑
- 查询objectType为doc的action是releaseddoc的lab标签第doc条的releaseddoc属性 @发布
- 查询有动作的对象类型 @product,epic,requirement,story,productplan,release,project,execution,task,bug,case,testtask,todo,doc,kanbancard,feedback,ticket,deploy,waterfall,opportunity,risk,issue,rule,meeting

- 查询 product 的对象操作 key @opened,edited,closed,undeleted

- 查询 product 的对象操作 value @创建,编辑,关闭,还原

- 查询 story 的对象操作 key @opened,edited,commented,frombug,changed,submitreview,reviewed,closed,activated,assigned

- 查询 story 的对象操作 value @创建,编辑,备注,转研发需求,变更,提交评审,审批,关闭,激活,指派

- 查询 productplan 的对象操作 key @opened,edited

- 查询 productplan 的对象操作 value @创建,编辑

- 查询 project 的对象操作 key @opened,edited,started,delayed,suspended,closed,activated,undeleted

- 查询 project 的对象操作 value @创建,编辑,开始,延期,挂起,关闭,激活,还原

- 查询 task 的对象操作 key @opened,edited,commented,assigned,confirmed,started,finished,paused,canceled,restarted,closed,activated

- 查询 task 的对象操作 value @创建,编辑,备注,指派,确认研发需求,开始,完成,暂停,取消,继续,关闭,激活

- 查询 bug 的对象操作 key @opened,edited,commented,assigned,confirmed,bugconfirmed,resolved,closed,activated

- 查询 bug 的对象操作 value @创建,编辑,备注,指派,确认研发需求,确认,解决,关闭,激活

- 查询 case 的对象操作 key @opened,edited,commented,reviewed,confirmed

- 查询 case 的对象操作 value @创建,编辑,备注,审批,确认研发需求

- 查询 testtask 的对象操作 key @opened,edited,started,blocked,closed,activated

- 查询 testtask 的对象操作 value @创建,编辑,开始,阻塞,关闭,激活

- 查询 todo 的对象操作 key @opened,edited

- 查询 todo 的对象操作 value @创建,编辑

- 查询 doc 的对象操作 key @releaseddoc,edited

- 查询 doc 的对象操作 value @发布,编辑

*/

global $lang, $app, $conifg;
$lang->SRCommon = '研发需求';
$app::$loadedLangs = array();
$app->loadLang('message');

$message = new messageModelTest();
$objectActions = $message->getObjectActionsTest();

r($objectActions) && p('product:opened')     && e('创建');       //查询objectType为product的action是opened的lab标签
r($objectActions) && p('story:frombug')      && e('转研发需求'); //查询objectType为story的action是frombug的lab标签
r($objectActions) && p('productplan:edited') && e('编辑');       //查询objectType为productplan的action是edited的lab标签
r($objectActions) && p('project:delayed')    && e('延期');       //查询objectType为project的action是delayed的lab标签
r($objectActions) && p('task:assigned')      && e('指派');       //查询objectType为task的action是assigned的lab标签
r($objectActions) && p('bug:closed')         && e('关闭');       //查询objectType为bug的action是closed的lab标签
r($objectActions) && p('case:opened')        && e('创建');       //查询objectType为case的action是opended的lab标签
r($objectActions) && p('testtask:started')   && e('开始');       //查询objectType为testtask的action是starteded的lab标签
r($objectActions) && p('todo:edited')        && e('编辑');       //查询objectType为todo的action是edited的lab标签
r($objectActions) && p('doc:releaseddoc')    && e('发布');       //查询objectType为doc的action是releaseddoc的lab标签

r(implode(',', array_keys($objectActions))) && p() && e('product,epic,requirement,story,productplan,release,project,execution,task,bug,case,testtask,todo,doc,kanbancard,feedback,ticket,deploy,waterfall,opportunity,risk,issue,rule,meeting'); // 查询有动作的对象类型

r(implode(',', array_keys($objectActions['product'])))   && p() && e('opened,edited,closed,undeleted'); // 查询 product 的对象操作 key
r(implode(',', array_values($objectActions['product']))) && p() && e('创建,编辑,关闭,还原');            // 查询 product 的对象操作 value

r(implode(',', array_keys($objectActions['story'])))   && p() && e('opened,edited,commented,frombug,changed,submitreview,reviewed,closed,activated,assigned'); // 查询 story 的对象操作 key
r(implode(',', array_values($objectActions['story']))) && p() && e('创建,编辑,备注,转研发需求,变更,提交评审,审批,关闭,激活,指派');                             // 查询 story 的对象操作 value

r(implode(',', array_keys($objectActions['productplan'])))   && p() && e('opened,edited'); // 查询 productplan 的对象操作 key
r(implode(',', array_values($objectActions['productplan']))) && p() && e('创建,编辑');     // 查询 productplan 的对象操作 value

r(implode(',', array_keys($objectActions['project'])))   && p() && e('opened,edited,started,delayed,suspended,closed,activated,undeleted'); // 查询 project 的对象操作 key
r(implode(',', array_values($objectActions['project']))) && p() && e('创建,编辑,开始,延期,挂起,关闭,激活,还原');                            // 查询 project 的对象操作 value

r(implode(',', array_keys($objectActions['task'])))   && p() && e('opened,edited,commented,assigned,confirmed,started,finished,paused,canceled,restarted,closed,activated'); // 查询 task 的对象操作 key
r(implode(',', array_values($objectActions['task']))) && p() && e('创建,编辑,备注,指派,确认研发需求,开始,完成,暂停,取消,继续,关闭,激活');                                    // 查询 task 的对象操作 value

r(implode(',', array_keys($objectActions['bug'])))   && p() && e('opened,edited,commented,assigned,confirmed,bugconfirmed,resolved,closed,activated'); // 查询 bug 的对象操作 key
r(implode(',', array_values($objectActions['bug']))) && p() && e('创建,编辑,备注,指派,确认研发需求,确认,解决,关闭,激活');                              // 查询 bug 的对象操作 value

r(implode(',', array_keys($objectActions['case'])))   && p() && e('opened,edited,commented,reviewed,confirmed'); // 查询 case 的对象操作 key
r(implode(',', array_values($objectActions['case']))) && p() && e('创建,编辑,备注,审批,确认研发需求');           // 查询 case 的对象操作 value

r(implode(',', array_keys($objectActions['testtask'])))   && p() && e('opened,edited,started,blocked,closed,activated'); // 查询 testtask 的对象操作 key
r(implode(',', array_values($objectActions['testtask']))) && p() && e('创建,编辑,开始,阻塞,关闭,激活');                  // 查询 testtask 的对象操作 value

r(implode(',', array_keys($objectActions['todo'])))   && p() && e('opened,edited'); // 查询 todo 的对象操作 key
r(implode(',', array_values($objectActions['todo']))) && p() && e('创建,编辑');     // 查询 todo 的对象操作 value

r(implode(',', array_keys($objectActions['doc'])))   && p() && e('releaseddoc,edited'); // 查询 doc 的对象操作 key
r(implode(',', array_values($objectActions['doc']))) && p() && e('发布,编辑');          // 查询 doc 的对象操作 value
