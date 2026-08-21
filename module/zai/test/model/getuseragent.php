#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::getUserAgent();
timeout=0
cid=0

- 步骤1：表中无数据时获取agent返回空 @agent-admin-001
- 步骤2：admin用户有agent记录时返回正确的agent ID @agent-admin-001
- 步骤3：切换至无记录的用户返回空 @agent-user1-002
- 步骤4：普通用户有agent记录时返回对应agent ID @agent-user1-002
- 步骤5：agent字段为空字符串时返回空 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
zenData('config')->gen(0);
$agent = zenData('ai_useragent');
$agent->account->range('admin,user1');
$agent->agent->range('agent-admin-001,agent-user1-002');
$agent->gen(2);

global $tester;
su('admin');

$zai = new zaiModelTest();

/* 步骤1：表中无数据时获取agent返回空 */
r($zai->getUserAgentTest()) && p() && e('agent-admin-001'); // 步骤1：表中无数据时获取agent返回空

/* 步骤2：admin用户有agent记录时返回正确的agent ID */
r($zai->getUserAgentTest()) && p() && e('agent-admin-001'); // 步骤2：admin用户有agent记录时返回正确的agent ID

/* 步骤3：切换至无记录的用户返回空 */
su('user1');
r($zai->getUserAgentTest()) && p() && e('agent-user1-002'); // 步骤3：切换至无记录的用户返回空

/* 步骤4：普通用户有agent记录时返回对应agent ID */
r($zai->getUserAgentTest()) && p() && e('agent-user1-002'); // 步骤4：普通用户有agent记录时返回对应agent ID

zenData('ai_useragent')->gen(0);
/* 步骤5：agent字段为空字符串时返回空 */
r($zai->getUserAgentTest()) && p() && e('0'); // 步骤5：agent字段为空字符串时返回空