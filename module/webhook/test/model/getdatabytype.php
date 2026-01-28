#!/usr/bin/env php
<?php

/**

title=测试 webhookModel::getDataByType();
timeout=0
cid=19694

- 步骤1：钉钉群组类型属性msgtype @markdown
- 步骤2：钉钉用户类型属性msgtype @markdown
- 步骤3：BearyChat类型
 - 属性text @Test Text
 - 属性markdown @true
- 步骤4：微信群组类型属性msgtype @text
- 步骤5：飞书用户类型属性msg_type @interactive
- 步骤6：通用类型
 - 属性text @测试动作文本
 - 属性objectType @bug
- 步骤7：飞书群组类型属性msg_type @interactive
- 步骤8：微信用户类型属性msgtype @text

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('webhook');
$table->id->range('1-10');
$table->type->range('dinggroup,dinguser,bearychat,wechatgroup,wechatuser,feishuuser,feishugroup,default');
$table->name->range('测试钉钉群组,测试钉钉用户,测试BearyChat,测试微信群组,测试微信用户,测试飞书用户,测试飞书群组,测试通用');
$table->url->range('https://webhook.test.com/1,https://webhook.test.com/2,https://webhook.test.com/3,https://webhook.test.com/4,https://webhook.test.com/5,https://webhook.test.com/6,https://webhook.test.com/7,https://webhook.test.com/8');
$table->params->range('text,title,objectType,text,title,text,title,text,objectType');
$table->deleted->range('0');
$table->gen(8);

$actionTable = zenData('action');
$actionTable->id->range('1-10');
$actionTable->objectType->range('story,task,bug');
$actionTable->objectID->range('1-3');
$actionTable->actor->range('admin,user1,user2');
$actionTable->action->range('opened,created,resolved');
$actionTable->comment->range('测试动作文本1,测试动作文本2,测试动作文本3');
$actionTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$webhookTest = new webhookModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($webhookTest->getDataByTypeTest('dinggroup', 'Test Title', 'Test Text', '13800138000', 'test@test.com', 'story', 1)) && p('msgtype') && e('markdown'); // 步骤1：钉钉群组类型
r($webhookTest->getDataByTypeTest('dinguser', 'Test Title', 'Test Text', '13800138001', 'test1@test.com', 'task', 2)) && p('msgtype') && e('markdown'); // 步骤2：钉钉用户类型
r($webhookTest->getDataByTypeTest('bearychat', 'Test Title', 'Test Text', '13800138002', 'test2@test.com', 'bug', 3)) && p('text,markdown') && e('Test Text,true'); // 步骤3：BearyChat类型
r($webhookTest->getDataByTypeTest('wechatgroup', 'Test Title', 'Test Text', '13800138003', 'test3@test.com', 'story', 1)) && p('msgtype') && e('text'); // 步骤4：微信群组类型
r($webhookTest->getDataByTypeTest('feishuuser', 'Test Title', 'Test Text', '13800138004', 'test4@test.com', 'task', 2)) && p('msg_type') && e('interactive'); // 步骤5：飞书用户类型
r($webhookTest->getDataByTypeTest('default', 'Test Title', 'Test Text', '13800138005', 'test5@test.com', 'bug', 3)) && p('text,objectType') && e('测试动作文本,bug'); // 步骤6：通用类型
r($webhookTest->getDataByTypeTest('feishugroup', 'Test Title', 'Test Text', '13800138006', 'test6@test.com', 'story', 1)) && p('msg_type') && e('interactive'); // 步骤7：飞书群组类型
r($webhookTest->getDataByTypeTest('wechatuser', 'Test Title', 'Test Text', '13800138007', 'test7@test.com', 'task', 2)) && p('msgtype') && e('text'); // 步骤8：微信用户类型