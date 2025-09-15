#!/usr/bin/env php
<?php

/**

title=测试 docZen::setSpacePageStorage();
timeout=0
cid=0

- 执行docTest模块的setSpacePageStorageTest方法，参数是'product', 'all', 1, 1, 1, 0 
 - 属性methodExists @yes
 - 属性paramTypes @valid
 - 属性typeValid @yes
- 执行docTest模块的setSpacePageStorageTest方法，参数是'project', 'draft', 2, 2, 2, 1 
 - 属性methodExists @yes
 - 属性paramTypes @valid
 - 属性typeValid @yes
- 执行docTest模块的setSpacePageStorageTest方法，参数是'execution', 'bysearch', 3, 3, 3, 2 
 - 属性methodExists @yes
 - 属性paramTypes @valid
 - 属性typeValid @yes
- 执行docTest模块的setSpacePageStorageTest方法，参数是'custom', 'all', 4, 4, 4, 3 
 - 属性methodExists @yes
 - 属性paramTypes @valid
 - 属性typeValid @yes
- 执行docTest模块的setSpacePageStorageTest方法，参数是'mine', 'draft', 5, 5, 5, 4 
 - 属性methodExists @yes
 - 属性paramTypes @valid
 - 属性typeValid @yes

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('user')->gen(5);

su('admin');

$docTest = new docTest();

r($docTest->setSpacePageStorageTest('product', 'all', 1, 1, 1, 0)) && p('methodExists,paramTypes,typeValid') && e('yes,valid,yes');
r($docTest->setSpacePageStorageTest('project', 'draft', 2, 2, 2, 1)) && p('methodExists,paramTypes,typeValid') && e('yes,valid,yes');
r($docTest->setSpacePageStorageTest('execution', 'bysearch', 3, 3, 3, 2)) && p('methodExists,paramTypes,typeValid') && e('yes,valid,yes');
r($docTest->setSpacePageStorageTest('custom', 'all', 4, 4, 4, 3)) && p('methodExists,paramTypes,typeValid') && e('yes,valid,yes');
r($docTest->setSpacePageStorageTest('mine', 'draft', 5, 5, 5, 4)) && p('methodExists,paramTypes,typeValid') && e('yes,valid,yes');