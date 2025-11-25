#!/usr/bin/env php
<?php

/**

title=测试 aiModel::serializeModel();
timeout=0
cid=15064

- 执行aiTest模块的serializeModelTest方法，参数是$validOpenAIModel
 - 属性type @openai-gpt35
 - 属性vendor @openai
- 执行aiTest模块的serializeModelTest方法，参数是$invalidModel  @0
- 执行aiTest模块的serializeModelTest方法，参数是$unknownVendorModel  @0
- 执行aiTest模块的serializeModelTest方法，参数是$proxyModel
 - 属性type @openai-gpt35
 - 属性vendor @openai
- 执行aiTest模块的serializeModelTest方法，参数是$azureModel
 - 属性type @openai-gpt35
 - 属性vendor @azure

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

su('admin');

$aiTest = new aiTest();

$validOpenAIModel = new stdclass();
$validOpenAIModel->name = 'Test OpenAI Model';
$validOpenAIModel->description = 'Test description';
$validOpenAIModel->type = 'openai-gpt35';
$validOpenAIModel->vendor = 'openai';
$validOpenAIModel->key = 'test-api-key-12345';
$validOpenAIModel->enabled = '1';

r($aiTest->serializeModelTest($validOpenAIModel)) && p('type,vendor') && e('openai-gpt35,openai');

$invalidModel = new stdclass();
$invalidModel->name = 'Invalid Model';
$invalidModel->description = 'Missing credentials';
$invalidModel->type = 'openai-gpt35';
$invalidModel->vendor = 'openai';
$invalidModel->enabled = '1';

r($aiTest->serializeModelTest($invalidModel)) && p() && e('0');

$unknownVendorModel = new stdclass();
$unknownVendorModel->name = 'Unknown Vendor Model';
$unknownVendorModel->description = 'Unknown vendor';
$unknownVendorModel->type = 'openai-gpt35';
$unknownVendorModel->vendor = 'unknown-vendor';
$unknownVendorModel->key = 'test-key';
$unknownVendorModel->enabled = '1';

r($aiTest->serializeModelTest($unknownVendorModel)) && p() && e('0');

$proxyModel = new stdclass();
$proxyModel->name = 'Proxy Model';
$proxyModel->description = 'Model with proxy';
$proxyModel->type = 'openai-gpt35';
$proxyModel->vendor = 'openai';
$proxyModel->key = 'test-api-key-proxy';
$proxyModel->proxyType = 'socks5';
$proxyModel->proxyAddr = '127.0.0.1:1080';
$proxyModel->enabled = '1';

r($aiTest->serializeModelTest($proxyModel)) && p('type,vendor') && e('openai-gpt35,openai');

$azureModel = new stdclass();
$azureModel->name = 'Azure Model';
$azureModel->description = 'Azure OpenAI model';
$azureModel->type = 'openai-gpt35';
$azureModel->vendor = 'azure';
$azureModel->key = 'azure-api-key';
$azureModel->resource = 'my-azure-resource';
$azureModel->deployment = 'gpt-35-turbo';
$azureModel->enabled = '1';

r($aiTest->serializeModelTest($azureModel)) && p('type,vendor') && e('openai-gpt35,azure');