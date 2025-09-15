#!/usr/bin/env php
<?php

/**

title=测试 productZen::saveAndModifyCookie4Browse();
timeout=0
cid=0

- 执行productTest模块的saveAndModifyCookie4BrowseTest方法，参数是1, 'main', 0, '', 'id_desc'  @1
- 执行productTest模块的saveAndModifyCookie4BrowseTest方法，参数是2, 'main', 0, '', 'id_desc'  @1
- 执行productTest模块的saveAndModifyCookie4BrowseTest方法，参数是1, 'main', 10, 'bymodule', 'id_desc'  @1
- 执行productTest模块的saveAndModifyCookie4BrowseTest方法，参数是1, 'dev', 0, 'bybranch', 'id_desc'  @1
- 执行productTest模块的saveAndModifyCookie4BrowseTest方法，参数是1, 'main', 5, 'bymodule', 'id_desc'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

zenData('product')->loadYaml('product', false, 2)->gen(10);

su('admin');

$productTest = new productTest();

r($productTest->saveAndModifyCookie4BrowseTest(1, 'main', 0, '', 'id_desc')) && p() && e('1');
r($productTest->saveAndModifyCookie4BrowseTest(2, 'main', 0, '', 'id_desc')) && p() && e('1');
r($productTest->saveAndModifyCookie4BrowseTest(1, 'main', 10, 'bymodule', 'id_desc')) && p() && e('1');
r($productTest->saveAndModifyCookie4BrowseTest(1, 'dev', 0, 'bybranch', 'id_desc')) && p() && e('1');
r($productTest->saveAndModifyCookie4BrowseTest(1, 'main', 5, 'bymodule', 'id_desc')) && p() && e('1');