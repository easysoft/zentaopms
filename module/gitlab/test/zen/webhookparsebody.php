#!/usr/bin/env php
<?php

/**

title=测试 gitlabZen::webhookParseBody();
timeout=0
cid=0

- 执行gitlabTest模块的webhookParseBodyTest方法，参数是$issueBody, 1 属性objectType @bug
- 执行gitlabTest模块的webhookParseBodyTest方法，参数是$unsupportedBody, 1  @0
- 执行gitlabTest模块的webhookParseBodyTest方法，参数是$emptyBody, 1  @0
- 执行gitlabTest模块的webhookParseBodyTest方法，参数是$pushBody, 1 属性type @push
- 执行gitlabTest模块的webhookParseBodyTest方法，参数是$incompleteIssueBody, 1  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

su('admin');

$gitlabTest = new gitlabTest();

// 测试步骤1：正常issue类型webhook解析
$issueBody = new stdclass;
$issueBody->object_kind = 'issue';
$issueBody->object_attributes = new stdclass;
$issueBody->object_attributes->action = 'open';
$issueBody->object_attributes->title = 'Test Issue';
$issueBody->labels = array((object)array('title' => 'bug/123'));
$issueBody->changes = new stdclass;
r($gitlabTest->webhookParseBodyTest($issueBody, 1)) && p('objectType') && e('bug');

// 测试步骤2：不支持的webhook类型解析
$unsupportedBody = new stdclass;
$unsupportedBody->object_kind = 'unsupported_type';
r($gitlabTest->webhookParseBodyTest($unsupportedBody, 1)) && p() && e('0');

// 测试步骤3：空object_kind的webhook解析
$emptyBody = new stdclass;
r($gitlabTest->webhookParseBodyTest($emptyBody, 1)) && p() && e('0');

// 测试步骤4：正常push类型webhook解析
$pushBody = new stdclass;
$pushBody->object_kind = 'push';
r($gitlabTest->webhookParseBodyTest($pushBody, 1)) && p('type') && e('push');

// 测试步骤5：缺少必要属性的issue webhook
$incompleteIssueBody = new stdclass;
$incompleteIssueBody->object_kind = 'issue';
r($gitlabTest->webhookParseBodyTest($incompleteIssueBody, 1)) && p() && e('0');