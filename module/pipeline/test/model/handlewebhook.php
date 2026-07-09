#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::handleWebhook();
timeout=0
cid=0

- 测试未知事件类型 >> unknown_event
- 测试事件类型不在pipeline配置的事件列表中 >> event_not_matched
- 测试Push事件且提交信息匹配 >> success
- 测试Push事件且提交信息不匹配 >> comment_not_matched
- 测试Push事件但无commits数据 >> no_commits

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$pipelineTest = new pipelineModelTest();

$pipeline = new stdclass();
$pipeline->id = 1;
$pipeline->event = 'push,tag_push';
$pipeline->comment = 'deploy';
$pipeline->providerID = 1;
$pipeline->externalPipeline = '123';
$pipeline->defaultBranch = 'main';

$commit = new stdclass();
$commit->message = 'feat: deploy to production';

$dataWithCommit = new stdclass();
$dataWithCommit->commits = array($commit);
$dataWithCommit->ref = 'refs/heads/main';
$dataWithCommit->after = 'abc123';

$commitNoMatch = new stdclass();
$commitNoMatch->message = 'feat: update docs';

$dataNoMatch = new stdclass();
$dataNoMatch->commits = array($commitNoMatch);

$dataNoCommit = new stdclass();

$pipelineNoPush = new stdclass();
$pipelineNoPush->event = 'tag_push,merge_requests';

r($pipelineTest->handleWebhookTest('Unknown Hook', $dataWithCommit, $pipeline)) && p() && e('unknown_event');
r($pipelineTest->handleWebhookTest('Push Hook', $dataWithCommit, $pipelineNoPush)) && p() && e('event_not_matched');
r($pipelineTest->handleWebhookTest('Push Hook', $dataWithCommit, $pipeline)) && p() && e('success');
r($pipelineTest->handleWebhookTest('Push Hook', $dataNoMatch, $pipeline)) && p() && e('comment_not_matched');
r($pipelineTest->handleWebhookTest('Push Hook', $dataNoCommit, $pipeline)) && p() && e('no_commits');
