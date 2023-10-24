#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->create();
cid=1
pid=1

测试创建用例1 >> created
测试创建用例2 >> created
测试创建用例3 >> created
测试创建重名用例1 >> exists
测试创建没有填写名字的用例 >> 『用例标题』不能为空。
测试创建没有填写类型的用例 >> 『用例类型』不能为空。

*/

$testcase1 = array('title' => '测试创建测试用例1');
$testcase2 = array('title' => '测试创建测试用例2', 'pri' => '1', 'type' => 'performance');
$testcase3 = array('title' => '测试创建测试用例3', 'keywords' => '测试关键词3', 'stage' => array('unittest', 'smoke'));

$no_title = array('title' => '');
$no_type  = array('type' => '', 'title' => '测试创建没有填写类型的用例');

$testcase = new testcaseTest();

r($testcase->createTest($testcase1)) && p('status') && e('created');                // 测试创建用例1
r($testcase->createTest($testcase2)) && p('status') && e('created');                // 测试创建用例2
r($testcase->createTest($testcase3)) && p('status') && e('created');                // 测试创建用例3
r($testcase->createTest($testcase1)) && p('status') && e('exists');                 // 测试创建重名用例1
r($testcase->createTest($no_title))  && p()         && e('『用例标题』不能为空。'); // 测试创建没有填写名字的用例
r($testcase->createTest($no_type))   && p()         && e('『用例类型』不能为空。'); // 测试创建没有填写类型的用例
