#!/usr/bin/env php
<?php

/**

title=测试 extensionModel::updateExtension();
timeout=0
cid=16472

- 测试步骤1：测试数据为空时更新插件 @0
- 测试步骤2：测试代号为空时更新插件 @0
- 测试步骤3：测试更新已存在插件的状态 @1
- 测试步骤4：测试dirs和files字段更新 @1
- 测试步骤5：测试不存在代号的更新 @1
- 测试步骤6：测试多字段更新 @1
- 测试步骤7：测试路径处理功能 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

// 数据准备
$extension = zenData('extension');
$extension->code->range('code1,code2,code3,code4,code5');
$extension->name->range('Extension1,Extension2,Extension3,Extension4,Extension5');
$extension->version->range('1.0,1.1,1.2,1.3,1.4');
$extension->status->range('available,installed,disabled');
$extension->type->range('extension{3},patch{2}');
$extension->dirs->range('["dir1","dir2"]{2},null{3}');
$extension->files->range('{"file1.php":"md5hash1"}{2},null{3}');
$extension->gen(5);

global $tester;
$tester->loadModel('extension');

// 测试步骤1：测试数据为空时更新插件返回的结果
$result1 = $tester->extension->updateExtension(array());

// 测试步骤2：测试代号为空时更新插件返回的结果
$result2 = $tester->extension->updateExtension(array('code' => '', 'status' => 'installed'));

// 测试步骤3：测试更新已存在插件的状态为installed
$result3 = $tester->extension->updateExtension(array('code' => 'code1', 'status' => 'installed'));

// 测试步骤4：测试更新插件的dirs和files字段JSON编码
$result4 = $tester->extension->updateExtension(array('code' => 'code2', 'dirs' => array('dir3', 'dir4'), 'files' => array('file2.php' => 'hash2')));

// 测试步骤5：测试更新不存在插件代号时的处理
$result5 = $tester->extension->updateExtension(array('code' => 'newcode', 'status' => 'disabled'));

// 测试步骤6：测试更新插件的多个字段组合
$result6 = $tester->extension->updateExtension(array('code' => 'code3', 'name' => 'Updated Extension', 'version' => '2.0', 'status' => 'disabled'));

// 测试步骤7：测试插件目录路径处理功能
$result7 = $tester->extension->updateExtension(array('code' => 'code4', 'dirs' => array('/app/module/test'), 'files' => array('/app/test.php' => 'newhash')));

// 断言验证
r($result1) && p() && e('0'); // 测试步骤1：测试数据为空时更新插件
r($result2) && p() && e('0'); // 测试步骤2：测试代号为空时更新插件
r($result3) && p() && e('1'); // 测试步骤3：测试更新已存在插件的状态
r($result4) && p() && e('1'); // 测试步骤4：测试dirs和files字段更新
r($result5) && p() && e('1'); // 测试步骤5：测试不存在代号的更新
r($result6) && p() && e('1'); // 测试步骤6：测试多字段更新
r($result7) && p() && e('1'); // 测试步骤7：测试路径处理功能