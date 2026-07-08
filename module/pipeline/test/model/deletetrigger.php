#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::deleteTrigger();
timeout=0
cid=0

- 测试步骤1：删除已存在的触发器 @0
- 测试步骤2：删除第二个触发器 @0
- 测试步骤3：删除第三个触发器 @0
- 测试步骤4：删除第四个触发器 @0
- 测试步骤5：删除第五个触发器 @0

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

r($pipelineTester->deleteTriggerTest($newTrigger(1, 1))) && p() && e('0');
r($pipelineTester->deleteTriggerTest($newTrigger(2, 2))) && p() && e('0');
r($pipelineTester->deleteTriggerTest($newTrigger(3, 3))) && p() && e('0');
r($pipelineTester->deleteTriggerTest($newTrigger(4, 4))) && p() && e('0');
r($pipelineTester->deleteTriggerTest($newTrigger(5, 5))) && p() && e('0');
