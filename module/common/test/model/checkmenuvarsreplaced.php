#!/usr/bin/env php
<?php

/**

title=测试 commonModel::checkMenuVarsReplaced();
timeout=0
cid=15658

- 执行commonTest模块的checkMenuVarsReplacedTest方法，参数是1  @public_static_method
- 执行commonTest模块的checkMenuVarsReplacedTest方法，参数是2  @no_return_type
- 执行commonTest模块的checkMenuVarsReplacedTest方法，参数是3  @no_parameters
- 执行commonTest模块的checkMenuVarsReplacedTest方法，参数是4  @has_doc_comment
- 执行commonTest模块的checkMenuVarsReplacedTest方法，参数是5  @correct_signature

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

su('admin');

$commonTest = new commonTest();

r($commonTest->checkMenuVarsReplacedTest(1)) && p() && e('public_static_method');
r($commonTest->checkMenuVarsReplacedTest(2)) && p() && e('no_return_type');
r($commonTest->checkMenuVarsReplacedTest(3)) && p() && e('no_parameters');
r($commonTest->checkMenuVarsReplacedTest(4)) && p() && e('has_doc_comment');
r($commonTest->checkMenuVarsReplacedTest(5)) && p() && e('correct_signature');