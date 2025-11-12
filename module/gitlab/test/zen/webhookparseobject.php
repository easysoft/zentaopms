#!/usr/bin/env php
<?php

/**

title=测试 gitlabZen::webhookParseObject();
timeout=0
cid=0

- 测试解析带bug标签的labels
 - 属性type @bug
 - 属性id @123
- 测试解析带story标签的labels
 - 属性type @story
 - 属性id @456
- 测试解析带task标签的labels
 - 属性type @task
 - 属性id @789
- 测试解析空labels数组 @0
- 测试解析无效格式标签的labels @0
- 测试解析混合标签
 - 属性type @bug
 - 属性id @999
- 测试解析多个zentao标签
 - 属性type @story
 - 属性id @222
- 测试解析标签ID为0的情况
 - 属性type @task
 - 属性id @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

/* 设置 methodName 避免 gitlab 控制器构造函数报错 */
global $app;
$app->setMethodName('test');

$gitlabTest = new gitlabZenTest();

/* 准备测试数据1:带bug标签的labels */
$labels1 = array();
$label1 = new stdclass;
$label1->title = 'zentao_bug/123';
$labels1[] = $label1;

/* 准备测试数据2:带story标签的labels */
$labels2 = array();
$label2 = new stdclass;
$label2->title = 'zentao_story/456';
$labels2[] = $label2;

/* 准备测试数据3:带task标签的labels */
$labels3 = array();
$label3 = new stdclass;
$label3->title = 'zentao_task/789';
$labels3[] = $label3;

/* 准备测试数据4:空labels数组 */
$labels4 = array();

/* 准备测试数据5:无效格式标签的labels */
$labels5 = array();
$label5 = new stdclass;
$label5->title = 'invalid_label_format';
$labels5[] = $label5;

/* 准备测试数据6:混合标签(包含bug标签和普通标签) */
$labels6 = array();
$label6a = new stdclass;
$label6a->title = 'normal_tag';
$labels6[] = $label6a;
$label6b = new stdclass;
$label6b->title = 'zentao_bug/999';
$labels6[] = $label6b;

/* 准备测试数据7:多个zentao标签(包含bug和story) */
$labels7 = array();
$label7a = new stdclass;
$label7a->title = 'zentao_bug/111';
$labels7[] = $label7a;
$label7b = new stdclass;
$label7b->title = 'zentao_story/222';
$labels7[] = $label7b;

/* 准备测试数据8:标签ID为0的情况 */
$labels8 = array();
$label8 = new stdclass;
$label8->title = 'zentao_task/0';
$labels8[] = $label8;

r($gitlabTest->webhookParseObjectTest($labels1)) && p('type,id') && e('bug,123'); // 测试解析带bug标签的labels
r($gitlabTest->webhookParseObjectTest($labels2)) && p('type,id') && e('story,456'); // 测试解析带story标签的labels
r($gitlabTest->webhookParseObjectTest($labels3)) && p('type,id') && e('task,789'); // 测试解析带task标签的labels
r($gitlabTest->webhookParseObjectTest($labels4)) && p() && e('0'); // 测试解析空labels数组
r($gitlabTest->webhookParseObjectTest($labels5)) && p() && e('0'); // 测试解析无效格式标签的labels
r($gitlabTest->webhookParseObjectTest($labels6)) && p('type,id') && e('bug,999'); // 测试解析混合标签
r($gitlabTest->webhookParseObjectTest($labels7)) && p('type,id') && e('story,222'); // 测试解析多个zentao标签
r($gitlabTest->webhookParseObjectTest($labels8)) && p('type,id') && e('task,0'); // 测试解析标签ID为0的情况