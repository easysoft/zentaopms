#!/usr/bin/env php
<?php

/**

title=测试 productZen::getExportData();
timeout=0
cid=0

- 执行productTest模块的getExportDataTest方法，参数是1, 'all', 'id_desc', 0, null 第0条的name属性 @产品1
- 执行productTest模块的getExportDataTest方法，参数是0, 'bysearch', 'id_desc', 1, null 第0条的name属性 @产品1
- 执行productTest模块的getExportDataTest方法，参数是0, 'all', 'id_desc', 0, null 第0条的id属性 @1
- 执行productTest模块的getExportDataTest方法，参数是-1, 'invalid', 'id_desc', 999, null  @0
- 执行productTest模块的getExportDataTest方法，参数是1, 'all', 'name_asc', 0, 'mockPager' 第0条的code属性 @product1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

su('admin');

$productTest = new productTest();

r($productTest->getExportDataTest(1, 'all', 'id_desc', 0, null)) && p('0:name') && e('产品1');
r($productTest->getExportDataTest(0, 'bysearch', 'id_desc', 1, null)) && p('0:name') && e('产品1');
r($productTest->getExportDataTest(0, 'all', 'id_desc', 0, null)) && p('0:id') && e('1');
r($productTest->getExportDataTest(-1, 'invalid', 'id_desc', 999, null)) && p() && e('0');
r($productTest->getExportDataTest(1, 'all', 'name_asc', 0, 'mockPager')) && p('0:code') && e('product1');