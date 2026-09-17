#!/usr/bin/env php
<?php

/**

title=测试 myModel->getReviewActionGroup();
timeout=0
cid=0

- 空对象类型列表返回空结果 @0
- 空对象ID列表返回空结果 @0
- 多对象类型返回每个对象的最新审批动作 @3
- 缺陷对象返回最新审批动作 @approvalreview
- 同一对象存在多条审批动作时返回最新对象ID @1
- 同一对象存在多条审批动作时返回最新动作人 @user2
- 同一对象存在多条审批动作时返回最新动作类型 @reviewed
- 普通评审动作返回对象ID @2
- 普通评审动作返回动作人 @user3
- 普通评审动作返回动作类型 @approvalreview
- 非审批动作不会覆盖最新对象ID @5
- 非审批动作不会覆盖最新审批动作人 @admin
- 非审批动作不会覆盖最新审批动作类型 @reviewed
- 重复对象ID不会产生重复结果 @2
- 视野过滤后只返回一个对象 @1
- 视野过滤后返回当前视野最新对象ID @1
- 视野过滤后返回当前视野最新动作人 @user4
- 视野过滤后返回当前视野最新动作类型 @reviewed

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$action = zenData('action');
$action->id->range('1-8');
$action->objectType->range('story,story,story,story,story,case,case,bug');
$action->objectID->range('1,1,1,2,1,5,5,1');
$action->actor->range('admin,user1,user2,user3,user4,admin,user2,admin');
$action->action->range('submitreview,reviewed,reviewed,approvalreview,reviewed,reviewed,submitreview,approvalreview');
$action->vision->range('rnd,rnd,rnd,rnd,or,rnd,rnd,rnd');
$action->gen(8);

su('admin');

global $config;
$config->vision = 'rnd';

$my = new myModelTest();
r(count($my->getReviewActionGroupTest(array()))) && p() && e('0'); // 空对象类型列表返回空结果
r(count($my->getReviewActionGroupTest(array('story' => array())))) && p() && e('0'); // 空对象ID列表返回空结果

$groups = $my->getReviewActionGroupTest(array('story' => array(1, 2), 'case' => array(5), 'bug' => array(1)));
r(count($groups)) && p() && e('3'); // 多对象类型返回每个对象的最新审批动作
r($groups['bug'][1]) && p('action') && e('approvalreview'); // 缺陷对象返回最新审批动作
r($groups['story'][1]) && p('objectID') && e('1'); // 同一对象存在多条审批动作时返回最新对象ID
r($groups['story'][1]) && p('actor') && e('user2'); // 同一对象存在多条审批动作时返回最新动作人
r($groups['story'][1]) && p('action') && e('reviewed'); // 同一对象存在多条审批动作时返回最新动作类型
r($groups['story'][2]) && p('objectID') && e('2'); // 普通评审动作返回对象ID
r($groups['story'][2]) && p('actor') && e('user3'); // 普通评审动作返回动作人
r($groups['story'][2]) && p('action') && e('approvalreview'); // 普通评审动作返回动作类型
r($groups['case'][5])  && p('objectID') && e('5'); // 非审批动作不会覆盖最新对象ID
r($groups['case'][5])  && p('actor') && e('admin'); // 非审批动作不会覆盖最新审批动作人
r($groups['case'][5])  && p('action') && e('reviewed'); // 非审批动作不会覆盖最新审批动作类型
r(count($my->getReviewActionGroupTest(array('story' => array(1, 1, 0, 2), 'case' => array(5, 5))))) && p() && e('2'); // 重复对象ID不会产生重复结果

$config->vision = 'or';
$orGroups = $my->getReviewActionGroupTest(array('story' => array(1, 2), 'case' => array(5)));
r(count($orGroups)) && p() && e('1'); // 视野过滤后只返回一个对象
r($orGroups['story'][1]) && p('objectID') && e('1'); // 视野过滤后返回当前视野最新对象ID
r($orGroups['story'][1]) && p('actor') && e('user4'); // 视野过滤后返回当前视野最新动作人
r($orGroups['story'][1]) && p('action') && e('reviewed'); // 视野过滤后返回当前视野最新动作类型
