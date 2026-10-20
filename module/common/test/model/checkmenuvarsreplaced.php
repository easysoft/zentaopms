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
 * - 步骤6：GitFox 问题菜单评审链接使用版本库 ID 替换 @评审问题|repo|review|repoID=2
 * - 步骤7：GitFox 问题菜单扫描链接使用版本库 ID 替换 @扫描问题|codescan|issue|repoID=2
 * - 步骤8：版本库 ID 为 0 时替换为 0 而非保留占位符 @评审问题|repo|review|repoID=0
 * - 步骤9：不含占位符的菜单链接保持不变 @评审问题|repo|review|repoID=2
 * - 步骤10：非问题应用菜单不进行替换 @地盘|my|index|
 *
 */

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$commonTaoTest   = new commonTaoTest();
$commonModelTest = new commonModelTest();

r($commonTaoTest->checkMenuVarsReplacedTest(1)) && p() && e('public_static_method');
r($commonTaoTest->checkMenuVarsReplacedTest(2)) && p() && e('no_return_type');
r($commonTaoTest->checkMenuVarsReplacedTest(3)) && p() && e('no_parameters');
r($commonTaoTest->checkMenuVarsReplacedTest(4)) && p() && e('has_doc_comment');
r($commonTaoTest->checkMenuVarsReplacedTest(5)) && p() && e('correct_signature');
r($commonModelTest->checkMenuVarsReplacedTest(1, 2)) && p() && e('评审问题|repo|review|repoID=2');  // 步骤6：评审链接替换版本库 ID
r($commonModelTest->checkMenuVarsReplacedTest(2, 2)) && p() && e('扫描问题|codescan|issue|repoID=2'); // 步骤7：扫描链接替换版本库 ID
r($commonModelTest->checkMenuVarsReplacedTest(3, 0)) && p() && e('评审问题|repo|review|repoID=0');  // 步骤8：版本库 ID 为 0 时替换占位符
r($commonModelTest->checkMenuVarsReplacedTest(4, 2)) && p() && e('评审问题|repo|review|repoID=2');  // 步骤9：无占位符时保持链接不变
r($commonModelTest->checkMenuVarsReplacedTest(5, 2)) && p() && e('地盘|my|index|');                // 步骤10：非问题应用菜单不受影响
