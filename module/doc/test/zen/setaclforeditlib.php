#!/usr/bin/env php
<?php

/**

title=测试 docZen::setAclForEditLib();
timeout=0
cid=0

- 执行docTest模块的setAclForEditLibTest方法，参数是$customLib 
 - 第aclList条的open属性 @Public
 - 第aclList条的private属性 @Private
 - 第aclList条的custom属性 @Custom
- 执行docTest模块的setAclForEditLibTest方法，参数是$apiLibWithProduct 属性result @1
- 执行docTest模块的setAclForEditLibTest方法，参数是$mineLib 
 - 第aclList条的open属性 @Public
 - 第aclList条的private属性 @Private
- 执行docTest模块的setAclForEditLibTest方法，参数是$productLib 
 - 第aclList条的default属性 @Default (Product Team Member)
 - 第aclList条的private属性 @Private (accessible to Product team members only)
- 执行docTest模块的setAclForEditLibTest方法，参数是$mainLib 第aclList条的default属性 @Default (Project Team Member)

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('doclib')->loadYaml('doclib_setaclforeditlib', false, 2)->gen(10);

su('admin');

$docTest = new docTest();

// 测试步骤1：测试custom类型文档库，应该移除default选项，保留open,private,custom
$customLib = (object)array('type' => 'custom', 'main' => 0);
r($docTest->setAclForEditLibTest($customLib)) && p('aclList:open,private,custom') && e('Public,Private,Custom');

// 测试步骤2：测试api类型文档库（product相关），应该返回成功结果
$apiLibWithProduct = (object)array('type' => 'api', 'product' => 1, 'main' => 0);
r($docTest->setAclForEditLibTest($apiLibWithProduct)) && p('result') && e('1');

// 测试步骤3：测试mine类型文档库，应该使用我的空间访问控制列表，只有open和private
$mineLib = (object)array('type' => 'mine', 'main' => 0);
r($docTest->setAclForEditLibTest($mineLib)) && p('aclList:open,private') && e('Public,Private');

// 测试步骤4：测试product类型文档库，应该设置default和private，移除open选项
$productLib = (object)array('type' => 'product', 'main' => 0);
r($docTest->setAclForEditLibTest($productLib)) && p('aclList:default,private') && e('Default (Product Team Member),Private (accessible to Product team members only)');

// 测试步骤5：测试主库（main=1）且非mine类型，应该只保留default选项
$mainLib = (object)array('type' => 'project', 'main' => 1);
r($docTest->setAclForEditLibTest($mainLib)) && p('aclList:default') && e('Default (Project Team Member)');