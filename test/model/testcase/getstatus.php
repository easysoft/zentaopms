#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->getStatus();
cid=1
pid=1

测试获取create的状态 >> normal
测试获取review pass 的状态 >> pass
测试获取review clarify 的状态 >> clarify
测试获取update的状态 >> wait
测试获取update 别人变更 的状态 >> 该记录可能已经被改动。请刷新页面重新编辑！

*/

$typeList = array('create', 'review', 'update');

$case1 = new stdclass();
$case1->status = 'pass';

$case2 = new stdclass();
$case2->status = 'clarify';

$case   = null;
$param1 = array('lastEditedDate' => '2022-03-04 00:00:00');

$testcase = new testcaseTest();

r($testcase->getStatusTest($typeList[0]))                 && p()    && e('normal');                                     // 测试获取create的状态
r($testcase->getStatusTest($typeList[1], $case1))         && p()    && e('pass');                                       // 测试获取review pass 的状态
r($testcase->getStatusTest($typeList[1], $case2))         && p()    && e('clarify');                                    // 测试获取review clarify 的状态
r($testcase->getStatusTest($typeList[2], $case))          && p('1') && e('wait');                                       // 测试获取update的状态
r($testcase->getStatusTest($typeList[2], $case, $param1)) && p()    && e('该记录可能已经被改动。请刷新页面重新编辑！'); // 测试获取update 别人变更 的状态