#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tutorial.class.php';

zdTable('user')->gen(5);

/**

title=测试 tutorialModel->getProduct();
timeout=0
cid=1

*/

$tutorial = new tutorialTest();

su('admin');
r($tutorial->getProductTest()) && p('id,program,PO,createdBy,reviewer') && e('1,0,admin,admin,admin'); // 测试是否能拿到 admin 的数据 id program PO createdBy reviewer
r($tutorial->getProductTest()) && p('plans:1')                          && e('Test plan');             // 测试是否能拿到 admin 的数据 plans

su('user1');
r($tutorial->getProductTest()) && p('id,program,PO,createdBy,reviewer') && e('1,0,user1,user1,user1'); // 测试是否能拿到 user1 的数据 id program PO createdBy reviewer
r($tutorial->getProductTest()) && p('plans:1')                          && e('Test plan');             // 测试是否能拿到 user1 的数据 plans
