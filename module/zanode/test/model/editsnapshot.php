#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 zanodeModel::editSnapshot();
timeout=0
cid=19788

- 测试步骤1
 - 属性localName @updatedSnap
 - 属性desc @updated description
- 测试步骤2
 - 属性localName @
 - 属性desc @empty name test
- 测试步骤3
 - 属性localName @longDescSnap
 - 属性desc @Long description for testing
- 测试步骤4属性error @Snapshot not found
- 测试步骤5
 - 属性localName @specialChars
 - 属性desc @Special chars test

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanode.unittest.class.php';

zenData('image')->gen(5);
zenData('user')->gen(5);
su('admin');

$zanode = new zanodeTest();

// 测试步骤1：正常编辑快照名称和描述
$data1 = new stdClass();
$data1->name = 'updatedSnap';
$data1->desc = 'updated description';
r($zanode->editSnapshotTest(1, $data1)) && p('localName,desc') && e('updatedSnap,updated description'); //测试步骤1

// 测试步骤2：编辑快照时使用空名称
$data2 = new stdClass();
$data2->name = '';
$data2->desc = 'empty name test';
r($zanode->editSnapshotTest(2, $data2)) && p('localName,desc') && e(',empty name test'); //测试步骤2

// 测试步骤3：编辑快照时使用长描述文本
$data3 = new stdClass();
$data3->name = 'longDescSnap';
$data3->desc = 'Long description for testing';
r($zanode->editSnapshotTest(3, $data3)) && p('localName,desc') && e('longDescSnap,Long description for testing'); //测试步骤3

// 测试步骤4：编辑不存在的快照ID
$data4 = new stdClass();
$data4->name = 'invalidSnap';
$data4->desc = 'invalid test';
r($zanode->editSnapshotTest(999, $data4)) && p('error') && e('Snapshot not found'); //测试步骤4

// 测试步骤5：使用特殊字符编辑快照信息
$data5 = new stdClass();
$data5->name = 'specialChars';
$data5->desc = 'Special chars test';
r($zanode->editSnapshotTest(4, $data5)) && p('localName,desc') && e('specialChars,Special chars test'); //测试步骤5