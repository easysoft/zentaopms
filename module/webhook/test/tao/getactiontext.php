#!/usr/bin/env php
<?php

/**

title=测试 webhookTao::getActionText();
timeout=0
cid=19709

- 执行webhookTest模块的getActionTextTest方法，参数是$data1, $action, $object, $users  @这是一个测试文本
- 执行webhookTest模块的getActionTextTest方法，参数是$data2, $action, $object, $users  @这是markdown内容
- 执行webhookTest模块的getActionTextTest方法，参数是$data3, $action, $object, $users  @这是文本内容
- 执行webhookTest模块的getActionTextTest方法，参数是$data4, $action, $object, $users  @卡片元素内容
- 执行webhookTest模块的getActionTextTest方法，参数是$data5, $action, $object, $users  @内容文本

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/webhook.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$webhookTest = new webhookTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 准备测试数据
$users = array('admin' => '系统管理员', 'user1' => '测试用户1');
$action = new stdclass();
$action->action = 'created';
$action->objectType = 'bug';
$action->objectID = 1;
$object = new stdclass();
$object->title = '测试Bug标题';

// 测试步骤1：markdown.text格式数据的文本提取
$data1 = new stdclass();
$data1->markdown = new stdclass();
$data1->markdown->text = '这是一个测试文本(http://example.com)后面的内容';
$data1->user = 'admin';
r($webhookTest->getActionTextTest($data1, $action, $object, $users)) && p() && e('这是一个测试文本');

// 测试步骤2：markdown.content格式数据的文本提取
$data2 = new stdclass();
$data2->markdown = new stdclass();
$data2->markdown->content = '这是markdown内容(http://test.com)其他内容';
$data2->user = 'admin';
r($webhookTest->getActionTextTest($data2, $action, $object, $users)) && p() && e('这是markdown内容');

// 测试步骤3：text.content格式数据的文本提取
$data3 = new stdclass();
$data3->text = new stdclass();
$data3->text->content = '这是文本内容(http://zentao.net)额外内容';
$data3->user = 'admin';
r($webhookTest->getActionTextTest($data3, $action, $object, $users)) && p() && e('这是文本内容');

// 测试步骤4：card.elements格式数据的文本提取
$data4 = new stdclass();
$data4->card = new stdclass();
$data4->card->elements = array();
$data4->card->elements[0] = new stdclass();
$data4->card->elements[0]->content = '卡片元素内容(http://example.org)其他';
$data4->user = 'admin';
r($webhookTest->getActionTextTest($data4, $action, $object, $users)) && p() && e('卡片元素内容');

// 测试步骤5：content格式数据的文本提取
$data5 = new stdclass();
$data5->content = new stdclass();
$data5->content->text = '内容文本(http://test.org)结束';
$data5->user = 'admin';
r($webhookTest->getActionTextTest($data5, $action, $object, $users)) && p() && e('内容文本');