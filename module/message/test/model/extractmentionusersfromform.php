#!/usr/bin/env php
<?php

/**

title=测试 messageModel::extractMentionUsersFromForm();
timeout=0
cid=17063

- 测试空表单配置 @0
- 测试无 editor 控件 @0
- 测试 editor 字段为空 @0
- 测试 editor 无 mention @0
- 测试单个 editor 单个 mention @user1
- 测试单个 editor 多个 mention @user1,user2
- 测试多个 editor 合并 mention @user1,user2
- 测试跨字段重复 mention 去重 @user1
- 测试非 editor 字段含 mention 被忽略 @user1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$mentionSpan = '<span class="mention-label" data-type="mention" data-id="%s">@%s</span>';

$htmlUser1   = sprintf($mentionSpan, 'user1', 'user1');
$htmlUser2   = sprintf($mentionSpan, 'user2', 'user2');
$htmlBoth    = $htmlUser1 . $htmlUser2;
$htmlPlain   = '<p>plain text</p>';

$editorOnlyConfig = array(
    'description' => array('control' => 'editor'),
);

$mixedControlConfig = array(
    'title'       => array('control' => 'input'),
    'description' => array('control' => 'editor'),
);

$twoEditorConfig = array(
    'description' => array('control' => 'editor'),
    'comment'     => array('control' => 'editor'),
);

$nonEditorConfig = array(
    'title' => array('control' => 'input'),
    'steps' => array('control' => 'textarea'),
);

$message = new messageModelTest();

r($message->extractMentionUsersFromFormTest(array(), (object)array('description' => $htmlUser1))) && p() && e('0');                                             // 测试空表单配置
r($message->extractMentionUsersFromFormTest($nonEditorConfig, (object)array('title' => $htmlUser1, 'steps' => $htmlUser2))) && p() && e('0');                   // 测试无 editor 控件
r($message->extractMentionUsersFromFormTest($editorOnlyConfig, (object)array('description' => ''))) && p() && e('0');                                           // 测试 editor 字段为空
r($message->extractMentionUsersFromFormTest($editorOnlyConfig, (object)array('description' => $htmlPlain))) && p() && e('0');                                   // 测试 editor 无 mention
r($message->extractMentionUsersFromFormTest($editorOnlyConfig, (object)array('description' => $htmlUser1))) && p() && e('user1');                               // 测试单个 editor 单个 mention
r($message->extractMentionUsersFromFormTest($editorOnlyConfig, (object)array('description' => $htmlBoth))) && p() && e('user1,user2');                          // 测试单个 editor 多个 mention
r($message->extractMentionUsersFromFormTest($twoEditorConfig, (object)array('description' => $htmlUser1, 'comment' => $htmlUser2))) && p() && e('user1,user2'); // 测试多个 editor 合并 mention
r($message->extractMentionUsersFromFormTest($twoEditorConfig, (object)array('description' => $htmlUser1, 'comment' => $htmlUser1))) && p() && e('user1');       // 测试跨字段重复 mention 去重
r($message->extractMentionUsersFromFormTest($mixedControlConfig, (object)array('title' => $htmlUser2, 'description' => $htmlUser1))) && p() && e('user1');      // 测试非 editor 字段含 mention 被忽略
