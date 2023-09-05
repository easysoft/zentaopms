#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';

zdTable('user')->gen('1');
zdTable('case')->gen('0');

su('admin');

/**

title=测试 testcaseModel->doCreate();
cid=1
pid=1

*/

$testcase1 = array('title' => '测试创建测试用例1');
$testcase2 = array('title' => '测试创建测试用例2', 'pri' => 1, 'type' => 'performance');
$testcase3 = array('title' => '测试创建测试用例3', 'keywords' => '测试关键词3', 'stage' => 'unittest,smoke');

$testcase = new testcaseTest();

r($testcase->doCreateTest($testcase1)) && p('title,pri,type') && e('测试创建测试用例1,3,feature');     // 测试创建用例1
r($testcase->doCreateTest($testcase2)) && p('title,pri,type') && e('测试创建测试用例2,1,performance'); // 测试创建用例2
r($testcase->doCreateTest($testcase3)) && p('title,pri,type') && e('测试创建测试用例3,3,feature');     // 测试创建用例3
