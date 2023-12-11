#!/usr/bin/env php
<?php

/**

title=测试 docModel->createAction();
cid=1

- 测试空数据 @0
- 给docID=1的文档添加一个收藏操作 @21
- 给docID=1的文档添加一个查看操作 @22
- 给docID=1,account=user1添加一个收藏操作 @23
- 给docID=1,account=user1添加一个查看操作 @24

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('docaction')->config('docaction')->gen(20);
zdTable('doc')->config('doc')->gen(10);
zdTable('user')->gen(5);
su('admin');

$docs     = array(0, 1);
$actions  = array('', 'collect', 'view');
$accounts = array('', 'user1');

$docTester = new docTest();
r($docTester->createActionTest($docs[0], $actions[0], $accounts[0])) && p() && e('0');  // 测试空数据
r($docTester->createActionTest($docs[1], $actions[1], $accounts[0])) && p() && e('21'); // 给docID=1的文档添加一个收藏操作
r($docTester->createActionTest($docs[1], $actions[2], $accounts[0])) && p() && e('22'); // 给docID=1的文档添加一个查看操作
r($docTester->createActionTest($docs[1], $actions[1], $accounts[1])) && p() && e('23'); // 给docID=1,account=user1添加一个收藏操作
r($docTester->createActionTest($docs[1], $actions[2], $accounts[1])) && p() && e('24'); // 给docID=1,account=user1添加一个查看操作
