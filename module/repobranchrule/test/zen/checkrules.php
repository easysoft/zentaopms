#!/usr/bin/env php
<?php

/**

title=测试 repobranchruleZen::checkRules();
timeout=0
cid=0

- 测试checkRules完整权限 @1
- 测试checkRules空数据 @1
- 测试checkRules hasPriv权限 @1
- 测试checkRules all合并 @1
- 测试checkRules混合模式 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repobranchrulezen.unittest.class.php';

zenData('user')->gen(5);
su('admin');

$tester = new repobranchruleZenTest();

$data1 = new stdclass();
$data1->allowCreate = array('option' => 'specify', 'value' => array('admin'));

$data2 = new stdclass();

$data3 = new stdclass();
$data3->allowDelete = array('option' => 'hasPriv', 'value' => array('admin'));

$data4 = new stdclass();
$data4->allowMergeFrom = array('option' => 'all', 'value' => array());

$data5 = new stdclass();
$data5->allowCreate = array('option' => 'hasPriv', 'value' => array('admin'));
$data5->allowMergeTo = array('option' => 'all', 'value' => array());

$v1 = $tester->checkRulesTest($data1);
$v2 = $tester->checkRulesTest($data2);
$v3 = $tester->checkRulesTest($data3);
$v4 = $tester->checkRulesTest($data4);
$v5 = $tester->checkRulesTest($data5);

r(is_object($v1) || is_bool($v1)) && p() && e('1');
r(is_object($v2) || is_bool($v2)) && p() && e('1');
r(is_object($v3) || is_bool($v3)) && p() && e('1');
r(is_object($v4) || is_bool($v4)) && p() && e('1');
r(is_object($v5) || is_bool($v5)) && p() && e('1');
