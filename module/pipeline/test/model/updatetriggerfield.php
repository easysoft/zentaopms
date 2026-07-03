#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::updateTriggerField();
timeout=0
cid=0

- 测试步骤1：更新event字段为tag_push @tag_push
- 测试步骤2：更新cron字段为定时表达式 @0 10 * * *
- 测试步骤3：更新comment字段为hotfix @hotfix
- 测试步骤4：更新event字段为空字符串 @~~
- 测试步骤5：更新后验证editedBy @admin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$newTrigger = function(int $repoID, int $pipelineID) {
    $trigger = new stdClass();
    $trigger->repoID      = $repoID;
    $trigger->pipelineID  = $pipelineID;
    $trigger->event       = 'push';
    $trigger->comment     = '';
    $trigger->cron        = '';
    $trigger->createdBy   = 'admin';
    $trigger->createdDate = helper::now();
    $trigger->editedBy    = 'admin';
    $trigger->editedDate  = helper::now();
    $trigger->deleted     = 0;
    return $trigger;
};

$pipelineTester = new pipelineModelTest();

r($pipelineTester->updateTriggerFieldTest($newTrigger(1, 1), 'event', 'tag_push')) && p('event') && e('tag_push');
r($pipelineTester->updateTriggerFieldTest($newTrigger(2, 2), 'cron', '0 10 * * *')) && p('cron') && e('0 10 * * *');
r($pipelineTester->updateTriggerFieldTest($newTrigger(3, 3), 'comment', 'hotfix')) && p('comment') && e('hotfix');
r($pipelineTester->updateTriggerFieldTest($newTrigger(4, 4), 'event', '')) && p('event') && e('~~');
r($pipelineTester->updateTriggerFieldTest($newTrigger(5, 5), 'event', 'push')) && p('editedBy') && e('admin');
