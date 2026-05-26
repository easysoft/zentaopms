#!/usr/bin/env php
<?php

/**

title=测试 messageModel::getMentionUsersFromHtml();
timeout=0
cid=17060

- 测试空 html @0
- 测试无 mention 标签的 html @0
- 测试单个 mention @user1
- 测试多个不同 mention @user1,user2
- 测试重复 mention 去重 @user1
- 测试单引号属性 @user1
- 测试错误的 data-type @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$mentionSpan = '<span class="mention-label" data-type="mention" data-id="%s">@%s</span>';

$htmlList = array(
    '',
    '<p>plain text</p>',
    sprintf($mentionSpan, 'user1', 'user1'),
    sprintf($mentionSpan, 'user1', 'user1') . sprintf($mentionSpan, 'user2', 'user2'),
    sprintf($mentionSpan, 'user1', 'user1') . sprintf($mentionSpan, 'user1', 'user1'),
    "<span class='mention-label' data-type='mention' data-id='user1'>@user1</span>",
    '<span class="mention-label" data-type="user" data-id="user1">@user1</span>',
);

$message = new messageModelTest();

r($message->getMentionUsersFromHtmlTest($htmlList[0])) && p() && e('0');           // 测试空 html
r($message->getMentionUsersFromHtmlTest($htmlList[1])) && p() && e('0');           // 测试无 mention 标签的 html
r($message->getMentionUsersFromHtmlTest($htmlList[2])) && p() && e('user1');       // 测试单个 mention
r($message->getMentionUsersFromHtmlTest($htmlList[3])) && p() && e('user1,user2'); // 测试多个不同 mention
r($message->getMentionUsersFromHtmlTest($htmlList[4])) && p() && e('user1');       // 测试重复 mention 去重
r($message->getMentionUsersFromHtmlTest($htmlList[5])) && p() && e('user1');       // 测试单引号属性
r($message->getMentionUsersFromHtmlTest($htmlList[6])) && p() && e('0');           // 测试错误的 data-type
