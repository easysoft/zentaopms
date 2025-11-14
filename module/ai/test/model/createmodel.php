#!/usr/bin/env php
<?php

/**

title=测试 aiModel::createModel();
timeout=0
cid=15011

- 执行aiTest模块的createModelTest方法，参数是$validOpenAIModel  @6
- 执行aiTest模块的createModelTest方法，参数是$validAzureModel  @7
- 执行aiTest模块的createModelTest方法，参数是$proxyModel  @8
- 执行aiTest模块的createModelTest方法，参数是$invalidModel  @0
- 执行aiTest模块的createModelTest方法，参数是$unknownVendorModel  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

$aiTable = zenData('ai_model');
$aiTable->id->range('1-5');
$aiTable->type->range('openai-gpt35{3},openai-gpt4{2}');
$aiTable->vendor->range('openai{3},azure{2}');
$aiTable->credentials->range('{"key":"test-key"}');
$aiTable->name->range('Test Model {1-5}');
$aiTable->desc->range('Test Description {1-5}');
$aiTable->enabled->range('1');
$aiTable->deleted->range('0');
$aiTable->createdBy->range('admin');
$aiTable->createdDate->range('`2024-01-01 10:00:00`');
$aiTable->gen(5);

su('admin');

$aiTest = new aiTest();

$validOpenAIModel = new stdclass();
$validOpenAIModel->name = 'Test OpenAI Model';
$validOpenAIModel->description = 'Test OpenAI GPT-3.5 model';
$validOpenAIModel->type = 'openai-gpt35';
$validOpenAIModel->vendor = 'openai';
$validOpenAIModel->key = 'sk-test-api-key-12345';

r($aiTest->createModelTest($validOpenAIModel)) && p() && e('6');

$validAzureModel = new stdclass();
$validAzureModel->name = 'Test Azure Model';
$validAzureModel->description = 'Test Azure OpenAI model';
$validAzureModel->type = 'openai-gpt35';
$validAzureModel->vendor = 'azure';
$validAzureModel->key = 'azure-api-key-test';
$validAzureModel->resource = 'my-azure-resource';
$validAzureModel->deployment = 'gpt-35-turbo-test';

r($aiTest->createModelTest($validAzureModel)) && p() && e('7');

$proxyModel = new stdclass();
$proxyModel->name = 'Test Proxy Model';
$proxyModel->description = 'Test model with proxy settings';
$proxyModel->type = 'openai-gpt4';
$proxyModel->vendor = 'openai';
$proxyModel->key = 'sk-proxy-test-key-67890';
$proxyModel->proxyType = 'socks5';
$proxyModel->proxyAddr = '127.0.0.1:1080';

r($aiTest->createModelTest($proxyModel)) && p() && e('8');

$invalidModel = new stdclass();
$invalidModel->name = 'Invalid Model';
$invalidModel->description = 'Model missing required credentials';
$invalidModel->type = 'openai-gpt35';
$invalidModel->vendor = 'openai';

r($aiTest->createModelTest($invalidModel)) && p() && e('0');

$unknownVendorModel = new stdclass();
$unknownVendorModel->name = 'Unknown Vendor Model';
$unknownVendorModel->description = 'Model with unknown vendor';
$unknownVendorModel->type = 'openai-gpt35';
$unknownVendorModel->vendor = 'unknown-vendor';
$unknownVendorModel->key = 'test-key-unknown';

r($aiTest->createModelTest($unknownVendorModel)) && p() && e('0');