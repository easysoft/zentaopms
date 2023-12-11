#!/usr/bin/env php
<?php

/**

title=测试 docModel->deleteAction();
cid=1

- 删除不存在的action @0
- 删除ID=1的操作 @1
- 删除ID=2的操作 @1
- 删除ID=11的操作 @1
- 删除ID=16的操作 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('docaction')->config('docaction')->gen(20);
zdTable('doc')->config('doc')->gen(10);
zdTable('user')->gen(5);
su('admin');

$actions = array(0, 1, 2, 11,16);
$docTester = new docTest();
r($docTester->deleteActionTest($actions[0])) && p() && e('0'); // 删除不存在的action
r($docTester->deleteActionTest($actions[1])) && p() && e('1'); // 删除ID=1的操作
r($docTester->deleteActionTest($actions[2])) && p() && e('1'); // 删除ID=2的操作
r($docTester->deleteActionTest($actions[3])) && p() && e('1'); // 删除ID=11的操作
r($docTester->deleteActionTest($actions[4])) && p() && e('1'); // 删除ID=16的操作
