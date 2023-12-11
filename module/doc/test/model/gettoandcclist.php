#!/usr/bin/env php
<?php

/**

title=测试 docModel->getToAndCcList();
cid=1

- 测试抄送人为空时，获取的收信人列表 @0
- 测试抄送人为admin时，获取的收信人列表 @admin
- 测试抄送人为admin,user1时，获取的抄送人列表属性1 @user1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('user')->gen(5);

$mailtoList = array('', 'admin', 'admin,user1');

$docTester = new docTest();
r($docTester->getToAndCcListTest($mailtoList[0])) && p()    && e('0');     // 测试抄送人为空时，获取的收信人列表
r($docTester->getToAndCcListTest($mailtoList[1])) && p('0') && e('admin'); // 测试抄送人为admin时，获取的收信人列表
r($docTester->getToAndCcListTest($mailtoList[2])) && p('1') && e('user1'); // 测试抄送人为admin,user1时，获取的抄送人列表
