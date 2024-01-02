#!/usr/bin/env php
<?php
/**

title=测试 docModel->getEditors();
cid=1

- 获取编辑过docID=0的用户列表 @0
- 获取编辑过docID=1的用户列表第0条的account属性 @user2
- 获取编辑过docID不存在的用户列表 @0
- 获取编辑过docID=0的用户数量 @0
- 获取编辑过docID=1的用户数量 @2
- 获取编辑过docID不存在的用户数量 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('action')->config('action')->gen(40);
zdTable('user')->gen(5);

$docIds = array(0, 1, 100);

$docTester = new docTest();
r($docTester->getEditorsTest($docIds[0])) && p()            && e('0');     // 获取编辑过docID=0的用户列表
r($docTester->getEditorsTest($docIds[1])) && p('0:account') && e('user2'); // 获取编辑过docID=1的用户列表
r($docTester->getEditorsTest($docIds[2])) && p()            && e('0');     // 获取编辑过docID不存在的用户列表

r(count($docTester->getEditorsTest($docIds[0]))) && p() && e('0'); // 获取编辑过docID=0的用户数量
r(count($docTester->getEditorsTest($docIds[1]))) && p() && e('2'); // 获取编辑过docID=1的用户数量
r(count($docTester->getEditorsTest($docIds[2]))) && p() && e('0'); // 获取编辑过docID不存在的用户数量
