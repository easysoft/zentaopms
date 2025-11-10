#!/usr/bin/env php
<?php

/**

title=测试 productZen::buildProductForClose();
timeout=0
cid=0

- 执行productTest模块的buildProductForCloseTest方法，参数是1
 - 属性status @close
 - 属性closedDate @2025-11-11
- 执行productTest模块的buildProductForCloseTest方法 属性status @close
- 执行productTest模块的buildProductForCloseTest方法，参数是999 属性status @close
- 执行productTest模块的buildProductForCloseTest方法，参数是1 属性status @close
- 执行productTest模块的buildProductForCloseTest方法，参数是1 属性status @close

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

$_POST['status'] = 'close';
$_POST['closedDate'] = date('Y-m-d');
$_POST['comment'] = 'Test close comment';
r($productTest->buildProductForCloseTest(1)) && p('status,closedDate') && e('close,2025-11-11');

$_POST['status'] = 'close';
$_POST['closedDate'] = date('Y-m-d');
$_POST['comment'] = '';
r($productTest->buildProductForCloseTest(0)) && p('status') && e('close');

$_POST['status'] = 'close';
$_POST['closedDate'] = date('Y-m-d');
$_POST['comment'] = 'Close non-existent product';
r($productTest->buildProductForCloseTest(999)) && p('status') && e('close');

$_POST['status'] = 'close';
$_POST['closedDate'] = date('Y-m-d');
$_POST['comment'] = '';
r($productTest->buildProductForCloseTest(1)) && p('status') && e('close');

$_POST['status'] = 'close';
$_POST['closedDate'] = date('Y-m-d');
$_POST['comment'] = 'This is a very long comment for closing the product. It should be processed correctly by the buildProductForClose method. The method should handle long text comments without any issues and store them properly in the database.';
r($productTest->buildProductForCloseTest(1)) && p('status') && e('close');
