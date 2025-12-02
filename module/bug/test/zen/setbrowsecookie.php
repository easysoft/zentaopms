#!/usr/bin/env php
<?php

/**

title=测试 bugZen::setBrowseCookie();
timeout=0
cid=15476

- 执行bugTest模块的setBrowseCookieTest方法，参数是$product, 'all', 'bymodule', 100, 'id_desc'  @1
- 执行bugTest模块的setBrowseCookieTest方法，参数是$product2, 'all', 'all', 0, 'id_desc'  @1
- 执行bugTest模块的setBrowseCookieTest方法，参数是$product3, 'branch1', 'all', 0, 'id_desc'  @1
- 执行bugTest模块的setBrowseCookieTest方法，参数是$product4, 'all', 'bysearch', 0, 'id_desc'  @1
- 执行bugTest模块的setBrowseCookieTest方法，参数是$product5, 'all', '', 200, 'id_asc'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// zendata数据准备
$table = zenData('product');
$table->id->range('1-5');
$table->name->range('产品1,产品2,产品3,产品4,产品5');
$table->type->range('normal{2},branch{3}');
$table->status->range('normal');
$table->gen(5);

su('admin');

$bugTest = new bugTest();

// 测试步骤1：正常产品和分支的浏览类型为bymodule
$product = new stdClass();
$product->id = 1;
$product->type = 'normal';
r($bugTest->setBrowseCookieTest($product, 'all', 'bymodule', 100, 'id_desc')) && p() && e('1');

// 测试步骤2：产品变更时清空cookie
$product2 = new stdClass();
$product2->id = 2;
$product2->type = 'normal';
r($bugTest->setBrowseCookieTest($product2, 'all', 'all', 0, 'id_desc')) && p() && e('1');

// 测试步骤3：分支变更时清空cookie
$product3 = new stdClass();
$product3->id = 3;
$product3->type = 'branch';
r($bugTest->setBrowseCookieTest($product3, 'branch1', 'all', 0, 'id_desc')) && p() && e('1');

// 测试步骤4：bysearch浏览类型时清空cookie
$product4 = new stdClass();
$product4->id = 4;
$product4->type = 'normal';
r($bugTest->setBrowseCookieTest($product4, 'all', 'bysearch', 0, 'id_desc')) && p() && e('1');

// 测试步骤5：空浏览类型时设置模块cookie
$product5 = new stdClass();
$product5->id = 5;
$product5->type = 'normal';
r($bugTest->setBrowseCookieTest($product5, 'all', '', 200, 'id_asc')) && p() && e('1');