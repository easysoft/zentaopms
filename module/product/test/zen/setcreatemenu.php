#!/usr/bin/env php
<?php

/**

title=测试 productZen::setCreateMenu();
timeout=0
cid=0

- 步骤1:product标签页属性tab @product
- 步骤2:program标签页,传入programID
 - 属性tab @program
 - 属性programID @1
- 步骤3:doc标签页,子菜单被移除属性docSubMenuUnset @1
- 步骤4:非mhtml视图属性tab @product
- 步骤5:projectstory模块
 - 属性rawModule @projectstory
 - 属性rawMethod @story

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

r($productTest->setCreateMenuTest(0, 'product', 'mhtml', 'product', 'create')) && p('tab') && e('product'); // 步骤1:product标签页
r($productTest->setCreateMenuTest(1, 'program', 'mhtml', 'product', 'create')) && p('tab,programID') && e('program,1'); // 步骤2:program标签页,传入programID
r($productTest->setCreateMenuTest(0, 'doc', 'mhtml', 'product', 'create')) && p('docSubMenuUnset') && e('1'); // 步骤3:doc标签页,子菜单被移除
r($productTest->setCreateMenuTest(0, 'product', '', 'product', 'create')) && p('tab') && e('product'); // 步骤4:非mhtml视图
r($productTest->setCreateMenuTest(0, 'product', 'mhtml', 'projectstory', 'story')) && p('rawModule,rawMethod') && e('projectstory,story'); // 步骤5:projectstory模块