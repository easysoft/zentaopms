#!/usr/bin/env php
<?php

/**

title=测试 webhookModel::getList();
timeout=0
cid=19698

- 测试步骤1：正常获取webhook列表，验证记录数量 @12
- 测试步骤2：测试默认排序（id_desc），验证第一条记录ID第12条的id属性 @12
- 测试步骤3：测试自定义排序（id_asc），验证第一条记录ID第1条的id属性 @1
- 测试步骤4：测试类型字段存在性，验证返回的记录包含type字段第12条的type属性 @feishugroup
- 测试步骤5：测试deleted字段过滤，验证所有返回记录的deleted字段都为0第1条的deleted属性 @0
- 测试步骤6：测试按名称排序，验证排序结果第6条的name属性 @BearyChat
- 测试步骤7：测试按类型排序，验证排序结果第4条的type属性 @bearychat

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
$table = zenData('webhook');
$table->id->range('1-15');
$table->type->range('dinggroup{3},bearychat{3},wechatgroup{3},feishugroup{3},custom{3}');
$table->name->range('钉钉群组{3},BearyChat{3},企业微信群{3},飞书群组{3},自定义{3}');
$table->url->range('http://example.com/webhook1,http://example.com/webhook2,http://example.com/webhook3,http://example.com/webhook4,http://example.com/webhook5,http://example.com/webhook6,http://example.com/webhook7,http://example.com/webhook8,http://example.com/webhook9,http://example.com/webhook10,http://example.com/webhook11,http://example.com/webhook12,http://example.com/webhook13,http://example.com/webhook14,http://example.com/webhook15');
$table->domain->range('http://zentao.com{15}');
$table->sendType->range('sync{8},async{7}');
$table->products->range('[]{10},1,2,3{2},4,5{2}');
$table->executions->range('[]{10},101,102,103{2},104,105{2}');
$table->params->range('text{15}');
$table->createdBy->range('admin{15}');
$table->deleted->range('0{12},1{3}');
$table->gen(15);

// 用户登录
su('admin');

// 创建测试实例
$webhookTest = new webhookModelTest();

$result1 = $webhookTest->getListTest();
$result2 = $webhookTest->getListTest('id_desc');
$result3 = $webhookTest->getListTest('id_asc');
$result4 = $webhookTest->getListTest('name_asc');
$result5 = $webhookTest->getListTest('type_asc');

r(count($result1)) && p() && e('12'); // 测试步骤1：正常获取webhook列表，验证记录数量
r($result2) && p('12:id') && e('12'); // 测试步骤2：测试默认排序（id_desc），验证第一条记录ID
r($result3) && p('1:id') && e('1'); // 测试步骤3：测试自定义排序（id_asc），验证第一条记录ID
r($result2) && p('12:type') && e('feishugroup'); // 测试步骤4：测试类型字段存在性，验证返回的记录包含type字段
r($result3) && p('1:deleted') && e('0'); // 测试步骤5：测试deleted字段过滤，验证所有返回记录的deleted字段都为0
r($result4) && p('6:name') && e('BearyChat'); // 测试步骤6：测试按名称排序，验证排序结果
r($result5) && p('4:type') && e('bearychat'); // 测试步骤7：测试按类型排序，验证排序结果