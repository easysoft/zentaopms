#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 zanodeModel->editSnapshot().
timeout=0
cid=1

- 测试编辑快照
 - 属性name @defaultSnap
 - 属性localName @test
 - 属性desc @test

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanode.unittest.class.php';

zenData('image')->loadYaml('image')->gen(1);
zenData('user')->gen(5);
su('admin');

$snapshot = new stdclass();
$snapshot->name = 'test';
$snapshot->desc = 'test';

$zanode = new zanodeTest();
r($zanode->editSnapshotTest(1, $snapshot)) && p('name,localName,desc') && e('defaultSnap,test,test'); //测试编辑快照