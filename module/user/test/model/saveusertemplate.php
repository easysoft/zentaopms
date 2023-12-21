#!/usr/bin/env php
<?php
/**
title=测试 userModel::saveUserTemplate();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

$table = zdTable('usertpl');
$table->type->range('story');
$table->gen(1);

$userTest = new userTest();

$template1 = (object)array('account' => 'admin', 'type' => 'story', 'title' => '',          'content' => '',          'public' => 0);
$template2 = (object)array('account' => 'admin', 'type' => 'story', 'title' => '模板名称1', 'content' => '模板内容1', 'public' => 0);
$template3 = (object)array
(
    'account' => '这是一个很长的创建人。这个很长的创建人到底有多长呢？这个很长的创建人长到超出了数据库字段的长度限制。',
    'type' => '这是一个很长的模板类型。这个很长的模板类型到底有多长呢？这个很长的模板类型长到超出了数据库字段的长度限制。',
    'title' => '这是一个很长的模板名称。这个很长的模板名称到底有多长呢？这个很长的模板名称长到超出了数据库字段的长度限制。数据库字段的长度限制是多少呢？数据库字段的长度限制是150。这个很长的模板名称的长度是多少呢？这个很长的模板名称的长度是120。这个很长的模板名称的长度才只有120啊，那还没有超过数据库字段的长度限制啊。现在超了O(∩_∩)O哈哈。',
    'content' => '模板内容3',
    'public' => 'public'
);
$template4 = (object)array('account' => 'admin', 'type' => 'story', 'title' => '模板名称4', 'content' => '模板内容4', 'public' => 0);

/* 测试必填项为空的情况。*/
$result = $userTest->saveUserTemplateTest($template1);
r($result) && p('result')         && e(0);                        // 模板名称和模板内容为空，返回 false。
r($result) && p('errors:account') && e('``');                     // 创建人无错误提示。
r($result) && p('errors:type')    && e('``');                     // 模板类型无错误提示。
r($result) && p('errors:title')   && e('『模板名称』不能为空。'); // 模板名称不能为空。
r($result) && p('errors:content') && e('『模板内容』不能为空。'); // 模板内容不能为空。
r($result) && p('errors:public')  && e('``');                     // 公共模板无错误提示。

/* 测试模板名称已存在的情况。*/
$result = $userTest->saveUserTemplateTest($template2);
r($result) && p('result')         && e(0);    // 模板名称已存在，返回 false。
r($result) && p('errors:account') && e('``'); // 创建人无错误提示。
r($result) && p('errors:type')    && e('``'); // 创建人无错误提示。
r($result) && p('errors:title')   && e('『模板名称』已经有『模板名称1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 模板名称已存在。
r($result) && p('errors:content') && e('``'); // 模板内容无错误提示。
r($result) && p('errors:public')  && e('``'); // 公共模板无错误提示。

/* 测试数据格式不符合数据库字段设置的情况。*/
$result = $userTest->saveUserTemplateTest($template3);
r($result) && p('result')         && e(0);                                                  // 创建人和模板名称过长，返回 false。
r($result) && p('errors:account') && e('『创建人』长度应当不超过『30』，且大于『0』。');    // 创建人过长。
r($result) && p('errors:type')    && e('『模板类型』长度应当不超过『30』，且大于『0』。');  // 模板类型过长。
r($result) && p('errors:title')   && e('『模板名称』长度应当不超过『150』，且大于『0』。'); // 模板名称过长。
r($result) && p('errors:content') && e('``');                                               // 模板内容无错误提示。
r($result) && p('errors:public')  && e('『公共模板』不符合格式，应当为:『/0|1/』。');       // 公共模板应当是数字。

/* 测试创建成功的情况。*/
$result = $userTest->saveUserTemplateTest($template4);
r($result) && p('result')         && e(1);    // 创建成功，返回 true。
r($result) && p('errors:account') && e('``'); // 创建人无错误提示。
r($result) && p('errors:type')    && e('``'); // 模板类型无错误提示。
r($result) && p('errors:title')   && e('``'); // 模板名称无错误提示。
r($result) && p('errors:content') && e('``'); // 模板内容无错误提示。
r($result) && p('errors:public')  && e('``'); // 公共模板无错误提示。
