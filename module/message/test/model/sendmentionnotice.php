#!/usr/bin/env php
<?php

/**

title=测试 messageModel::sendMentionNotice();
timeout=0
cid=17062

- 测试文档创建时发送 mention 通知
 - 属性notifyCount @1
 - 属性objectType @message
 - 属性action @1
 - 属性mentionUser @user1
 - 属性status @wait
 - 属性createdBy @admin
- 测试文档无 mention 不发送通知 @0
- 测试文档编辑仅通知新增 mention
 - 属性notifyCount @1
 - 属性mentionUser @user2
- 测试动态不存在不发送通知 @0
- 测试消息设置为空不发送通知 @0
- 测试 Bug 创建时从 steps 提取 mention 并通知
 - 属性notifyCount @1
 - 属性mentionUser @user1
- 测试被 @ 用户与操作者相同时不发送通知 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('notify')->gen(0);

$action = zenData('action');
$action->id->range('1-5');
$action->objectType->range('doc{5}');
$action->actor->range('admin{5}');
$action->gen(5);

$user = zenData('user');
$user->account->range('admin,user1,user2');
$user->realname->range('管理员,用户1,用户2');
$user->gen(3);

su('admin');

$mentionSpan = '<span class="mention-label" data-type="mention" data-id="%s">@%s</span>';

/**
 * 构建带 mention 的文档 rawContent。
 * Build doc raw content with mentions.
 *
 * @param  array $mentionIds
 * @access public
 * @return string
 */
function buildMentionDocRawContent(array $mentionIds): string
{
    $delta = array();
    foreach($mentionIds as $id) $delta[] = array('insert' => "@{$id}", 'attributes' => array('mention' => array('id' => $id)));

    $content = array(
        'type'   => 'page',
        'blocks' => array(
            'type'     => 'block',
            'flavour'  => 'affine:page',
            'children' => array(
                array(
                    'type'     => 'block',
                    'flavour'  => 'affine:paragraph',
                    'props'    => array('text' => array('delta' => $delta)),
                    'children' => array(),
                ),
            ),
        ),
    );

    return json_encode($content);
}

$docWithMention = (object)array(
    'id'         => 1,
    'title'      => '测试文档',
    'rawContent' => buildMentionDocRawContent(array('user1')),
);

$docWithoutMention = (object)array(
    'id'         => 2,
    'title'      => '空文档',
    'rawContent' => buildMentionDocRawContent(array()),
);

$docOldMention = (object)array(
    'id'         => 3,
    'title'      => '旧文档',
    'rawContent' => buildMentionDocRawContent(array('user1')),
);

$docNewMention = (object)array(
    'id'         => 3,
    'title'      => '新文档',
    'rawContent' => buildMentionDocRawContent(array('user1', 'user2')),
);

$bugWithMention = (object)array(
    'id'    => 1,
    'title' => '测试Bug',
    'steps' => sprintf($mentionSpan, 'user1', 'user1'),
);

$docMentionActor = (object)array(
    'id'         => 4,
    'title'      => '提及操作者',
    'rawContent' => buildMentionDocRawContent(array('admin')),
);

$message = new messageModelTest();

r($message->sendMentionNoticeTest('doc', 'create', 1, $docWithMention)) && p('notifyCount,objectType,action,mentionUser,status,createdBy') && e('1,message,1,user1,wait,admin'); // 测试文档创建时发送 mention 通知
r($message->sendMentionNoticeTest('doc', 'create', 2, $docWithoutMention)) && p('notifyCount') && e('0'); // 测试文档无 mention 不发送通知
r($message->sendMentionNoticeTest('doc', 'edit', 3, $docNewMention, $docOldMention)) && p('notifyCount,mentionUser') && e('1,user2'); // 测试文档编辑仅通知新增 mention
r($message->sendMentionNoticeTest('doc', 'create', 999, $docWithMention)) && p('notifyCount') && e('0'); // 测试动态不存在不发送通知
r($message->sendMentionNoticeTest('doc', 'create', 4, $docWithMention, null, 'empty')) && p('notifyCount') && e('0'); // 测试消息设置为空不发送通知
r($message->sendMentionNoticeTest('bug', 'create', 5, $bugWithMention)) && p('notifyCount,mentionUser') && e('1,user1'); // 测试 Bug 创建时从 steps 提取 mention 并通知
r($message->sendMentionNoticeTest('doc', 'create', 1, $docMentionActor)) && p('notifyCount') && e('0'); // 测试被提到的用户与操作者相同时不发送通知
