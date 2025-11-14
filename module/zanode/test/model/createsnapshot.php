#!/usr/bin/env php
<?php

/**

title=测试 zanodeModel::createSnapshot();
timeout=0
cid=19785

- 执行zanode模块的createSnapshotTest方法，参数是10, '192.168.1.100', 8080, 'valid_token_123', $validSnapshot  @失败
- 执行zanode模块的createSnapshotTest方法，参数是11, '192.168.1.101', 0, 'valid_token_456', $validSnapshot  @失败
- 执行zanode模块的createSnapshotTest方法，参数是12, '192.168.1.102', 8080, '', $validSnapshot  @失败
- 执行zanode模块的createSnapshotTest方法，参数是13, '0.0.0.0', 8080, 'valid_token_789', $validSnapshot  @失败
- 执行zanode模块的createSnapshotTest方法，参数是10, '192.168.1.100', 8080, 'valid_token_123', $invalidSnapshot  @失败

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanode.unittest.class.php';

$hostTable = zenData('host');
$hostTable->id->range('10-14');
$hostTable->name->range('test-node10,test-node11,test-node12,test-node13,test-node14');
$hostTable->type->range('node{5}');
$hostTable->status->range('running{3},ready{2}');
$hostTable->extranet->range('192.168.1.100,192.168.1.101,192.168.1.102,192.168.1.103,127.0.0.1');
$hostTable->zap->range('8080{3},8081{1},0{1}');
$hostTable->tokenSN->range('valid_token_123,valid_token_456,valid_token_789,empty_token,\'\'');
$hostTable->osName->range('Ubuntu20.04{2},CentOS7{2},Windows10{1}');
$hostTable->parent->range('0{5}');
$hostTable->deleted->range('0{5}');
$hostTable->gen(5);

zenData('user')->gen(5);

su('admin');

$zanode = new zanodeTest();

$validSnapshot = array('name' => 'test-snapshot', 'desc' => '正常快照描述');
$invalidSnapshot = array('name' => '', 'desc' => '无效名称快照');
$largeSnapshot = array('name' => str_repeat('a', 256), 'desc' => '名称过长快照');

r($zanode->createSnapshotTest(10, '192.168.1.100', 8080, 'valid_token_123', $validSnapshot)) && p() && e('失败');
r($zanode->createSnapshotTest(11, '192.168.1.101', 0, 'valid_token_456', $validSnapshot)) && p() && e('失败');
r($zanode->createSnapshotTest(12, '192.168.1.102', 8080, '', $validSnapshot)) && p() && e('失败');
r($zanode->createSnapshotTest(13, '0.0.0.0', 8080, 'valid_token_789', $validSnapshot)) && p() && e('失败');
r($zanode->createSnapshotTest(10, '192.168.1.100', 8080, 'valid_token_123', $invalidSnapshot)) && p() && e('失败');