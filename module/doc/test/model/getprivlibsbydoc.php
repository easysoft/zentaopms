#!/usr/bin/env php
<?php
/**

title=测试 docModel->getPrivLibsByDoc();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('doc')->config('doc')->gen(50);
zdTable('user')->gen(5);
su('admin');

$docTester = new docTest();
r($docTester->getPrivLibsByDocTest()) && p('11,12,13,6,7,8,16,20,17,18,25,26') && e('11,12,13,6,7,8,16,20,17,18,25,26'); // 获取有权限访问的文档库
