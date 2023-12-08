#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tutorial.class.php';

zdTable('user')->gen(5);

/**

title=测试 tutorialModel->getProject();
cid=1
pid=1

*/

$tutorial = new tutorialTest();

su('admin');
r($tutorial->getProjectTest()) && p('id,type,PM,PO,QD,RD') && e('2,project,admin,admin,admin,admin'); // 测试是否能拿到 admin 的数据

su('user1');
r($tutorial->getProjectTest()) && p('id,type,PM,PO,QD,RD') && e('2,project,user1,user1,user1,user1'); // 测试是否能拿到 admin 的数据
