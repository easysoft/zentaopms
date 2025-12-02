#!/usr/bin/env php
<?php

/**

title=测试 convertModel::checkDBName();
timeout=0
cid=15762

- 执行convertTest模块的checkDBNameTest方法，参数是'zentao_db'  @1
- 执行convertTest模块的checkDBNameTest方法，参数是'test_db123'  @1
- 执行convertTest模块的checkDBNameTest方法，参数是''  @0
- 执行convertTest模块的checkDBNameTest方法，参数是'123database'  @0
- 执行convertTest模块的checkDBNameTest方法，参数是'test-db'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

su('admin');

$convertTest = new convertTest();

r($convertTest->checkDBNameTest('zentao_db')) && p() && e('1');
r($convertTest->checkDBNameTest('test_db123')) && p() && e('1');
r($convertTest->checkDBNameTest('')) && p() && e('0');
r($convertTest->checkDBNameTest('123database')) && p() && e('0');
r($convertTest->checkDBNameTest('test-db')) && p() && e('0');