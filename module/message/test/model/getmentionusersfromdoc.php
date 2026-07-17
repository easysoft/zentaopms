#!/usr/bin/env php
<?php

/**

title=测试 messageModel::getMentionUsersFromDoc();
timeout=0
cid=17061

- 测试空内容 @0
- 测试无效 JSON @0
- 测试无 mention 的段落 @0
- 测试单个 mention @user1
- 测试多个 mention @user1,user2
- 测试重复 mention 去重 @user1
- 测试 mention id 含空格 @user1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

/**
 * 构建 BlockSuite 文档 rawContent。
 * Build BlockSuite doc raw content for tests.
 *
 * @param  array $mentionIds
 * @access public
 * @return string
 */
function buildDocRawContent(array $mentionIds = array()): string
{
    $delta = array(array('insert' => 'plain text'));
    if(!empty($mentionIds))
    {
        $delta = array();
        foreach($mentionIds as $id) $delta[] = array('insert' => "@{$id}", 'attributes' => array('mention' => array('id' => $id)));
    }

    $paragraph = array(
        'type'     => 'block',
        'flavour'  => 'affine:paragraph',
        'props'    => array('text' => array('delta' => $delta)),
        'children' => array(),
    );

    $content = array(
        'type'   => 'page',
        'blocks' => array(
            'type'     => 'block',
            'flavour'  => 'affine:page',
            'children' => array($paragraph),
        ),
    );

    return json_encode($content);
}

/**
 * 构建包含多个段落的文档。
 * Build doc with multiple paragraphs.
 *
 * @param  array $mentionIdsPerParagraph
 * @access public
 * @return string
 */
function buildDocRawContentWithParagraphs(array $mentionIdsPerParagraph): string
{
    $paragraphs = array();
    foreach($mentionIdsPerParagraph as $mentionIds)
    {
        $delta = array();
        foreach($mentionIds as $id) $delta[] = array('insert' => "@{$id}", 'attributes' => array('mention' => array('id' => $id)));

        $paragraphs[] = array(
            'type'     => 'block',
            'flavour'  => 'affine:paragraph',
            'props'    => array('text' => array('delta' => $delta)),
            'children' => array(),
        );
    }

    $content = array(
        'type'   => 'page',
        'blocks' => array(
            'type'     => 'block',
            'flavour'  => 'affine:page',
            'children' => $paragraphs,
        ),
    );

    return json_encode($content);
}

$rawContentList = array(
    '',
    'not-json',
    buildDocRawContent(),
    buildDocRawContent(array('user1')),
    buildDocRawContentWithParagraphs(array(array('user1'), array('user2'))),
    buildDocRawContent(array('user1', 'user1')),
    buildDocRawContent(array(' user1 ')),
);

$message = new messageModelTest();

r($message->getMentionUsersFromDocTest($rawContentList[0])) && p() && e('0');           // 测试空内容
r($message->getMentionUsersFromDocTest($rawContentList[1])) && p() && e('0');           // 测试无效 JSON
r($message->getMentionUsersFromDocTest($rawContentList[2])) && p() && e('0');           // 测试无 mention 的段落
r($message->getMentionUsersFromDocTest($rawContentList[3])) && p() && e('user1');       // 测试单个 mention
r($message->getMentionUsersFromDocTest($rawContentList[4])) && p() && e('user1,user2'); // 测试多个 mention
r($message->getMentionUsersFromDocTest($rawContentList[5])) && p() && e('user1');       // 测试重复 mention 去重
r($message->getMentionUsersFromDocTest($rawContentList[6])) && p() && e('user1');       // 测试 mention id 含空格
