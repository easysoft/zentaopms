#!/usr/bin/env php
<?php

/**

title=测试 aiModel::unserializeModel();
timeout=0
cid=15074

- 执行aiTest模块的unserializeModelTest方法，参数是$model1
 - 属性id @1
 - 属性name @GPT-4
 - 属性type @openai-gpt4
 - 属性vendor @openai
 - 属性apiKey @sk-test123
 - 属性endpoint @https://api.openai.com
 - 属性proxyHost @proxy.com
 - 属性proxyPort @8080
- 执行aiTest模块的unserializeModelTest方法，参数是$model2
 - 属性id @2
 - 属性name @Claude-3
 - 属性apiKey @sk-claude456
 - 属性model @claude-3
 - 属性endpoint @https://api.anthropic.com
- 执行aiTest模块的unserializeModelTest方法，参数是$model3
 - 属性id @3
 - 属性name @OpenAI / GPT-3.5
 - 属性type @openai-gpt35
- 执行aiTest模块的unserializeModelTest方法，参数是$model4
 - 属性id @4
 - 属性token @zhipu789
 - 属性baseUrl @https://open.bigmodel.cn
 - 属性temperature @0.8
 - 属性maxTokens @2048
 - 属性proxyHost @127.0.0.1
- 执行aiTest模块的unserializeModelTest方法，参数是$model5
 - 属性id @5
 - 属性name @Test-Model
 - 属性type @openai-gpt4
 - 属性vendor @test
- 执行aiTest模块的unserializeModelTest方法，参数是$model6
 - 属性id @6
 - 属性name @Test-Model-2
 - 属性apiKey @test-key
 - 属性endpoint @https://test.com

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$aiTest = new aiModelTest();

// 4. 强制要求：必须包含至少6个测试步骤

// 测试步骤1：完整模型配置反序列化（包含credentials和proxy）
$model1 = new stdclass();
$model1->id = 1;
$model1->name = 'GPT-4';
$model1->type = 'openai-gpt4';
$model1->vendor = 'openai';
$model1->desc = '高性能AI模型';
$model1->enabled = '1';
$model1->deleted = '0';
$model1->credentials = '{"apiKey":"sk-test123","endpoint":"https://api.openai.com"}';
$model1->proxy = '{"host":"proxy.com","port":"8080","auth":"user:pass"}';
r($aiTest->unserializeModelTest($model1)) && p('id,name,type,vendor,apiKey,endpoint,proxyHost,proxyPort') && e('1,GPT-4,openai-gpt4,openai,sk-test123,https://api.openai.com,proxy.com,8080');

// 测试步骤2：只有credentials无proxy的模型反序列化
$model2 = new stdclass();
$model2->id = 2;
$model2->name = 'Claude-3';
$model2->type = 'openai-gpt35';
$model2->vendor = 'claude';
$model2->desc = '智能对话模型';
$model2->enabled = '1';
$model2->deleted = '0';
$model2->credentials = '{"apiKey":"sk-claude456","model":"claude-3","endpoint":"https://api.anthropic.com"}';
$model2->proxy = '';
r($aiTest->unserializeModelTest($model2)) && p('id,name,apiKey,model,endpoint') && e('2,Claude-3,sk-claude456,claude-3,https://api.anthropic.com');

// 测试步骤3：空name字段的模型反序列化
$model3 = new stdclass();
$model3->id = 3;
$model3->name = '';
$model3->type = 'openai-gpt35';
$model3->vendor = 'openai';
$model3->desc = '测试模型';
$model3->enabled = '1';
$model3->deleted = '0';
$model3->credentials = '{"apiKey":"","endpoint":""}';
$model3->proxy = '';
r($aiTest->unserializeModelTest($model3)) && p('id,name,type') && e('3,OpenAI / GPT-3.5,openai-gpt35');

// 测试步骤4：复杂JSON credentials的解析
$model4 = new stdclass();
$model4->id = 4;
$model4->name = 'Gemini-Pro';
$model4->type = 'baidu-ernie';
$model4->vendor = 'gemini';
$model4->desc = '测试模型';
$model4->enabled = '1';
$model4->deleted = '0';
$model4->credentials = '{"token":"zhipu789","baseUrl":"https://open.bigmodel.cn","temperature":0.8,"maxTokens":2048}';
$model4->proxy = '{"host":"127.0.0.1","port":"1080"}';
r($aiTest->unserializeModelTest($model4)) && p('id,token,baseUrl,temperature,maxTokens,proxyHost') && e('4,zhipu789,https://open.bigmodel.cn,0.8,2048,127.0.0.1');

// 测试步骤5：空credentials的边界情况处理
$model5 = new stdclass();
$model5->id = 5;
$model5->name = 'Test-Model';
$model5->type = 'openai-gpt4';
$model5->vendor = 'test';
$model5->desc = '测试模型';
$model5->enabled = '1';
$model5->deleted = '0';
$model5->credentials = '{}';
$model5->proxy = '';
r($aiTest->unserializeModelTest($model5)) && p('id,name,type,vendor') && e('5,Test-Model,openai-gpt4,test');

// 测试步骤6：测试proxy为null的情况
$model6 = new stdclass();
$model6->id = 6;
$model6->name = 'Test-Model-2';
$model6->type = 'openai-gpt35';
$model6->vendor = 'openai';
$model6->desc = '测试模型';
$model6->enabled = '1';
$model6->deleted = '0';
$model6->credentials = '{"apiKey":"test-key","endpoint":"https://test.com"}';
$model6->proxy = null;
r($aiTest->unserializeModelTest($model6)) && p('id,name,apiKey,endpoint') && e('6,Test-Model-2,test-key,https://test.com');