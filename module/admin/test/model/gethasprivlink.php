#!/usr/bin/env php
<?php

/**

title=测试 adminModel::getHasPrivLink();
timeout=0
cid=0

- 测试步骤1：正常菜单有权限链接测试
 -  @company
 - 属性1 @index
- 测试步骤2：没有链接的菜单测试 @0
- 测试步骤3：有链接但无权限的菜单备选链接测试
 -  @company
 - 属性1 @index
- 测试步骤4：自定义模块索引权限测试
 -  @custom
 - 属性1 @index
- 测试步骤5：空链接的菜单测试 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/admin.unittest.class.php';

su('admin');

$adminTest = new adminTest();

r($adminTest->getHasPrivLinkTest(array('link' => 'System|company|index|'))) && p('0,1') && e('company,index');        // 测试步骤1：正常菜单有权限链接测试
r($adminTest->getHasPrivLinkTest(array())) && p() && e('0');                                                          // 测试步骤2：没有链接的菜单测试
r($adminTest->getHasPrivLinkTest(array('link' => 'Invalid|invalid|method|', 'links' => array('company|index|')))) && p('0,1') && e('company,index');    // 测试步骤3：有链接但无权限的菜单备选链接测试
r($adminTest->getHasPrivLinkTest(array('link' => 'System|custom|index|'))) && p('0,1') && e('custom,index');        // 测试步骤4：自定义模块索引权限测试
r($adminTest->getHasPrivLinkTest(array('link' => ''))) && p() && e('0');                                             // 测试步骤5：空链接的菜单测试