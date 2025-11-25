#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('user')->gen('1');
zenData('case')->gen('0');

su('admin');

/**

title=测试 testcaseModel->doCreateSpec();
cid=19031

- 测试创建用例1 文件 ''
 - 属性title @标题1
 - 属性precondition @前置条件1
 - 属性version @1
 - 属性files @0
- 测试创建用例1 文件 array()
 - 属性title @标题1
 - 属性precondition @前置条件1
 - 属性version @1
 - 属性files @0
- 测试创建用例2 文件 '1,2'
 - 属性title @标题2
 - 属性precondition @前置条件2
 - 属性version @2
 - 属性files @1,2
- 测试创建用例2 文件 array(1,2)
 - 属性title @标题2
 - 属性precondition @前置条件2
 - 属性version @2
 - 属性files @0,1
- 测试创建用例3 文件 ''
 - 属性title @标题1
 - 属性precondition @前置条件1
 - 属性version @1
 - 属性files @0
- 测试创建用例4 文件 ''
 - 属性title @标题1
 - 属性precondition @前置条件1
 - 属性version @1
 - 属性files @0
- 测试创建用例5 文件 ''
 - 属性title @标题1
 - 属性precondition @前置条件1
 - 属性version @1
 - 属性files @0

*/

$caseID = array(1, 2, 3, 4, 5);
$files  = array('', array(), '1,2', array(1, 2));

$case1 = new stdClass();
$case1->title        = '标题1';
$case1->version      = 1;
$case1->precondition = '前置条件1';

$case2 = new stdClass();
$case2->title        = '标题2';
$case2->version      = 2;
$case2->precondition = '前置条件2';

$testcase = new testcaseTest();

r($testcase->doCreateSpecTest($caseID[0], $case1, $files[0])) && p('title|precondition|version|files', '|') && e('标题1|前置条件1|1|0');     // 测试创建用例1 文件 ''
r($testcase->doCreateSpecTest($caseID[0], $case1, $files[1])) && p('title|precondition|version|files', '|') && e('标题1|前置条件1|1|0');     // 测试创建用例1 文件 array()
r($testcase->doCreateSpecTest($caseID[1], $case2, $files[2])) && p('title|precondition|version|files', '|') && e('标题2|前置条件2|2|1,2');     // 测试创建用例2 文件 '1,2'
r($testcase->doCreateSpecTest($caseID[1], $case2, $files[3])) && p('title|precondition|version|files', '|') && e('标题2|前置条件2|2|0,1');     // 测试创建用例2 文件 array(1,2)
r($testcase->doCreateSpecTest($caseID[2], $case1, $files[0])) && p('title|precondition|version|files', '|') && e('标题1|前置条件1|1|0');     // 测试创建用例3 文件 ''
r($testcase->doCreateSpecTest($caseID[3], $case1, $files[0])) && p('title|precondition|version|files', '|') && e('标题1|前置条件1|1|0');     // 测试创建用例4 文件 ''
r($testcase->doCreateSpecTest($caseID[4], $case1, $files[0])) && p('title|precondition|version|files', '|') && e('标题1|前置条件1|1|0');     // 测试创建用例5 文件 ''
