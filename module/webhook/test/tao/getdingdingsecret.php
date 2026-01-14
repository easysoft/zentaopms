#!/usr/bin/env php
<?php

/**

title=测试 webhookTao::getDingdingSecret();
timeout=0
cid=19710

- 执行webhookTest模块的getDingdingSecretTest方法，参数是$normalWebhook 属性url @https://oapi.dingtalk.com/
- 执行webhookTest模块的getDingdingSecretTest方法，参数是$missingAgentId 属性agentId @『钉钉AgentId』不能为空。
- 执行webhookTest模块的getDingdingSecretTest方法，参数是$missingAppKey 属性appKey @『钉钉AppKey』不能为空。
- 执行webhookTest模块的getDingdingSecretTest方法，参数是$missingAppSecret 属性appSecret @『钉钉AppSecret』不能为空。
- 执行webhookTest模块的getDingdingSecretTest方法，参数是$emptyWebhook 属性agentId @『钉钉AgentId』不能为空。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$webhookTest = new webhookTaoTest();

// 创建完整的正常webhook对象
$normalWebhook = new stdClass();
$normalWebhook->agentId = '123456789';
$normalWebhook->appKey = 'testappkey';
$normalWebhook->appSecret = 'testappsecret';

r($webhookTest->getDingdingSecretTest($normalWebhook)) && p('url') && e('https://oapi.dingtalk.com/');

// 缺少agentId
$missingAgentId = new stdClass();
$missingAgentId->agentId = '';
$missingAgentId->appKey = 'testappkey';
$missingAgentId->appSecret = 'testappsecret';

r($webhookTest->getDingdingSecretTest($missingAgentId)) && p('agentId') && e('『钉钉AgentId』不能为空。');

// 缺少appKey
$missingAppKey = new stdClass();
$missingAppKey->agentId = '123456789';
$missingAppKey->appKey = '';
$missingAppKey->appSecret = 'testappsecret';

r($webhookTest->getDingdingSecretTest($missingAppKey)) && p('appKey') && e('『钉钉AppKey』不能为空。');

// 缺少appSecret
$missingAppSecret = new stdClass();
$missingAppSecret->agentId = '123456789';
$missingAppSecret->appKey = 'testappkey';
$missingAppSecret->appSecret = '';

r($webhookTest->getDingdingSecretTest($missingAppSecret)) && p('appSecret') && e('『钉钉AppSecret』不能为空。');

// 所有参数都为空
$emptyWebhook = new stdClass();
$emptyWebhook->agentId = '';
$emptyWebhook->appKey = '';
$emptyWebhook->appSecret = '';

r($webhookTest->getDingdingSecretTest($emptyWebhook)) && p('agentId') && e('『钉钉AgentId』不能为空。');