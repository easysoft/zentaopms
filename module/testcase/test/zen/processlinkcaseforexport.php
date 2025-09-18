#!/usr/bin/env php
<?php

/**

title=- 执行testcaseTest模块的processLinkCaseForExportTest方法 属性linkCase @用户登录功能测试(
timeout=0
cid=2

- 执行testcaseTest模块的processLinkCaseForExportTest方法 属性linkCase @用户登录功能测试(#2)
- 执行testcaseTest模块的processLinkCaseForExportTest方法 属性linkCase @密码修改测试(#3)
- 执行testcaseTest模块的processLinkCaseForExportTest方法 属性linkCase @7
- 执行testcaseTest模块的processLinkCaseForExportTest方法 属性linkCase @
- 执行testcaseTest模块的processLinkCaseForExportTest方法 属性linkCase @系统配置管理(#8)

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

su('admin');

$testcaseTest = new testcaseZenTest();

// 测试步骤1：正常情况下处理包含关联用例ID的linkCase字段
r($testcaseTest->processLinkCaseForExportTest((object)array('id' => 1, 'linkCase' => '2'), array('2' => '用户登录功能测试'))) && p('linkCase') && e('用户登录功能测试(#2)');

// 测试步骤2：处理单个关联用例ID字段3
r($testcaseTest->processLinkCaseForExportTest((object)array('id' => 2, 'linkCase' => '3'), array('3' => '密码修改测试'))) && p('linkCase') && e('密码修改测试(#3)');

// 测试步骤3：处理不存在的关联用例ID字段7
r($testcaseTest->processLinkCaseForExportTest((object)array('id' => 3, 'linkCase' => '7'), array('6' => '文件上传测试'))) && p('linkCase') && e('7');

// 测试步骤4：处理空的linkCase字段
r($testcaseTest->processLinkCaseForExportTest((object)array('id' => 4, 'linkCase' => ''), array())) && p('linkCase') && e('');

// 测试步骤5：处理包含空格的单个linkCase字段8
r($testcaseTest->processLinkCaseForExportTest((object)array('id' => 5, 'linkCase' => ' 8 '), array('8' => '系统配置管理'))) && p('linkCase') && e('系统配置管理(#8)');