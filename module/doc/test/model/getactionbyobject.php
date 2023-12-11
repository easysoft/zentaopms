#!/usr/bin/env php
<?php

/**

title=测试 docModel->getActionByObject();
cid=1

- 测试空数据 @0
- 测试查询docID=1的数据 @0
- 测试查询docID=1, action=collect的数据
 - 属性doc @1
 - 属性action @collect
- 测试查询docID=1, action=view的数据属性doc @0
属性action @0
- 测试查询docID=1, action=collect, account=user1的数据属性doc @0
属性action @0
- 测试查询docID=1, action=view, account=user1的数据 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('docaction')->config('docaction')->gen(20);
zdTable('user')->gen(5);
su('admin');

$docs     = array(0, 1);
$actions  = array('', 'collect', 'view');
$accounts = array('', 'user1');

$docTester = new docTest();
r($docTester->getActionByObjectTest($docs[0], $actions[0], $accounts[0])) && p()             && e('0');         // 测试空数据
r($docTester->getActionByObjectTest($docs[1], $actions[0], $accounts[0])) && p()             && e('0');         // 测试查询docID=1的数据
r($docTester->getActionByObjectTest($docs[1], $actions[1], $accounts[0])) && p('doc,action') && e('1,collect'); // 测试查询docID=1, action=collect的数据
r($docTester->getActionByObjectTest($docs[1], $actions[2], $accounts[0])) && p('doc,action') && e('0');         // 测试查询docID=1, action=view的数据
r($docTester->getActionByObjectTest($docs[1], $actions[1], $accounts[1])) && p('doc,action') && e('0');         // 测试查询docID=1, action=collect, account=user1的数据
r($docTester->getActionByObjectTest($docs[1], $actions[2], $accounts[1])) && p()             && e('0');         // 测试查询docID=1, action=view, account=user1的数据
