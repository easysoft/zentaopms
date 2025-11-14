#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 extensionModel::removePackage();
timeout=0
cid=16470

- 步骤1：删除正常extension类型的插件，返回数组类型 @~~
- 步骤2：删除patch类型的插件，应返回空数组 @~~
- 步骤3：删除不存在的插件，返回空数组 @~~
- 步骤4：删除空字符串插件代码，返回空数组 @~~
- 步骤5：删除具有文件和目录的插件，验证返回类型 @1
- 步骤6：验证patch类型插件确实返回空数组 @0
- 步骤7：测试具有复杂文件结构的插件删除 @~~

*/

// zendata数据准备
$table = zenData('extension');
$table->id->range('1-10');
$table->name->range('正常插件,补丁插件,测试插件{8}');
$table->code->range('normal_ext,patch_ext,complex_ext,empty_ext,test_ext{5},nonexistent{1}');
$table->version->range('1.0.0,2.0.0,1.5.0{8}');
$table->author->range('Test Author{10}');
$table->type->range('extension{7},patch{2},plugin{1}');
$table->files->range('{"test/file1.php":"md5hash1","test/file2.js":"md5hash2"}{3},{}{4},{"complex/deep/file.php":"hash123","www/assets/style.css":"hash456"}{3}');
$table->dirs->range('["test/dir1","test/dir2"]{3},[]{4},["complex/deep","www/assets"]{3}');
$table->status->range('available{6},installed{2},deactivated{2}');
$table->gen(10);

global $tester;
$tester->loadModel('extension');

// 执行测试步骤
r($tester->extension->removePackage('normal_ext')) && p() && e('~~');         // 步骤1：删除正常extension类型的插件，返回数组类型
r($tester->extension->removePackage('patch_ext')) && p() && e('~~');          // 步骤2：删除patch类型的插件，应返回空数组
r($tester->extension->removePackage('nonexistent')) && p() && e('~~');       // 步骤3：删除不存在的插件，返回空数组
r($tester->extension->removePackage('')) && p() && e('~~');                  // 步骤4：删除空字符串插件代码，返回空数组
r(is_array($tester->extension->removePackage('test_ext'))) && p() && e(1);   // 步骤5：删除具有文件和目录的插件，验证返回类型
r(count($tester->extension->removePackage('patch_ext'))) && p() && e(0);     // 步骤6：验证patch类型插件确实返回空数组
r($tester->extension->removePackage('complex_ext')) && p() && e('~~');       // 步骤7：测试具有复杂文件结构的插件删除