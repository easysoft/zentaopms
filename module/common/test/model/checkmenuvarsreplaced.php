#!/usr/bin/env php
<?php
/**
 *
 * title=测试 commonModel::checkMenuVarsReplaced();
 * timeout=0
 * cid=0
 *
 * - 执行commonTaoTest模块的checkMenuVarsReplacedTest方法，测试用例1 @public_static_method
 * - 执行commonTaoTest模块的checkMenuVarsReplacedTest方法，测试用例2 @no_return_type
 * - 执行commonTaoTest模块的checkMenuVarsReplacedTest方法，测试用例3 @no_parameters
 * - 执行commonTaoTest模块的checkMenuVarsReplacedTest方法，测试用例4 @has_doc_comment
 * - 执行commonTaoTest模块的checkMenuVarsReplacedTest方法，测试用例5 @correct_signature
 *
 */

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$commonTaoTest = new commonTaoTest();

r($commonTaoTest->checkMenuVarsReplacedTest(1)) && p() && e('public_static_method');
r($commonTaoTest->checkMenuVarsReplacedTest(2)) && p() && e('no_return_type');
r($commonTaoTest->checkMenuVarsReplacedTest(3)) && p() && e('no_parameters');
r($commonTaoTest->checkMenuVarsReplacedTest(4)) && p() && e('has_doc_comment');
r($commonTaoTest->checkMenuVarsReplacedTest(5)) && p() && e('correct_signature');
