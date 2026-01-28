#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('user')->gen('1');
zenData('case')->gen('0');

su('admin');

/**

title=测试 testcaseModel->doCreate();
cid=19030

- 测试创建用例1
 - 属性title @测试创建测试用例1
 - 属性pri @3
 - 属性type @feature
- 测试创建用例2
 - 属性title @测试创建测试用例2
 - 属性pri @1
 - 属性type @performance
- 测试创建用例3
 - 属性title @测试创建测试用例3
 - 属性pri @3
 - 属性type @feature

*/

$testcase1 = array('title' => '测试创建测试用例1');
$testcase2 = array('title' => '测试创建测试用例2', 'pri' => 1, 'type' => 'performance');
$testcase3 = array('title' => '测试创建测试用例3', 'keywords' => '测试关键词3', 'stage' => 'unittest,smoke');

$testcase = new testcaseTaoTest();

r($testcase->doCreateTest($testcase1)) && p('title,pri,type') && e('测试创建测试用例1,3,feature');     // 测试创建用例1
r($testcase->doCreateTest($testcase2)) && p('title,pri,type') && e('测试创建测试用例2,1,performance'); // 测试创建用例2
r($testcase->doCreateTest($testcase3)) && p('title,pri,type') && e('测试创建测试用例3,3,feature');     // 测试创建用例3
