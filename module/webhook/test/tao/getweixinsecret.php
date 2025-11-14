#!/usr/bin/env php
<?php

/**

title=测试 webhookTao::getWeixinSecret();
timeout=0
cid=19712

- 执行webhookTest模块的getWeixinSecretTest方法，参数是$normalWebhook 属性url @https://qyapi.weixin.qq.com/cgi-bin/
- 执行webhookTest模块的getWeixinSecretTest方法，参数是$missingCorpId 属性wechatCorpId @『企业ID』不能为空。
- 执行webhookTest模块的getWeixinSecretTest方法，参数是$missingCorpSecret 属性wechatCorpSecret @『应用的凭证密钥』不能为空。
- 执行webhookTest模块的getWeixinSecretTest方法，参数是$missingAgentId 属性wechatAgentId @『企业应用的ID』不能为空。
- 执行webhookTest模块的getWeixinSecretTest方法，参数是$emptyWebhook 属性wechatCorpId @『企业ID』不能为空。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/webhook.unittest.class.php';

su('admin');

$webhookTest = new webhookTest();

// 创建完整的正常webhook对象
$normalWebhook = new stdClass();
$normalWebhook->wechatCorpId = 'wxcorp123456789';
$normalWebhook->wechatCorpSecret = 'wechat_secret_123456789';
$normalWebhook->wechatAgentId = '1000001';

r($webhookTest->getWeixinSecretTest($normalWebhook)) && p('url') && e('https://qyapi.weixin.qq.com/cgi-bin/');

// 缺少企业ID
$missingCorpId = new stdClass();
$missingCorpId->wechatCorpId = '';
$missingCorpId->wechatCorpSecret = 'wechat_secret_123456789';
$missingCorpId->wechatAgentId = '1000001';

r($webhookTest->getWeixinSecretTest($missingCorpId)) && p('wechatCorpId') && e('『企业ID』不能为空。');

// 缺少企业秘钥
$missingCorpSecret = new stdClass();
$missingCorpSecret->wechatCorpId = 'wxcorp123456789';
$missingCorpSecret->wechatCorpSecret = '';
$missingCorpSecret->wechatAgentId = '1000001';

r($webhookTest->getWeixinSecretTest($missingCorpSecret)) && p('wechatCorpSecret') && e('『应用的凭证密钥』不能为空。');

// 缺少应用ID
$missingAgentId = new stdClass();
$missingAgentId->wechatCorpId = 'wxcorp123456789';
$missingAgentId->wechatCorpSecret = 'wechat_secret_123456789';
$missingAgentId->wechatAgentId = '';

r($webhookTest->getWeixinSecretTest($missingAgentId)) && p('wechatAgentId') && e('『企业应用的ID』不能为空。');

// 所有参数都为空
$emptyWebhook = new stdClass();
$emptyWebhook->wechatCorpId = '';
$emptyWebhook->wechatCorpSecret = '';
$emptyWebhook->wechatAgentId = '';

r($webhookTest->getWeixinSecretTest($emptyWebhook)) && p('wechatCorpId') && e('『企业ID』不能为空。');