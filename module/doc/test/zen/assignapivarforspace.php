#!/usr/bin/env php
<?php

/**

title=测试 docZen::assignApiVarForSpace();
timeout=0
cid=0

- 执行docTest模块的assignApiVarForSpaceTest方法，参数是'product', 'all', 'lib', 1, array
 - 属性pager @pager
 - 属性hasDocs @1
- 执行docTest模块的assignApiVarForSpaceTest方法，参数是'product', 'all', 'api', 1, array
 - 属性pager @pager
 - 属性hasApiList @1
- 执行docTest模块的assignApiVarForSpaceTest方法，参数是'product', 'all', 'lib', 1, array 属性canExport @0
- 执行docTest模块的assignApiVarForSpaceTest方法，参数是'project', 'all', 'lib', 1, array 属性canExport @0
- 执行docTest模块的assignApiVarForSpaceTest方法，参数是'product', 'all', 'lib', 1, array 属性pager @pager
- 执行docTest模块的assignApiVarForSpaceTest方法，参数是'product', 'all', 'lib', 1, array 属性pager @pager

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doczen.unittest.class.php';

zendata('doclib')->gen(10);
zendata('doc')->gen(20);
zendata('api')->gen(15);

su('admin');

$docTest = new docZenTest();

r($docTest->assignApiVarForSpaceTest('product', 'all', 'lib', 1, array(1 => 'testlib'), 1, 0, 0, 'id_desc', 0, 20, 20, 1)) && p('pager,hasDocs') && e('pager,1');
r($docTest->assignApiVarForSpaceTest('product', 'all', 'api', 1, array(1 => 'apilib'), 1, 0, 0, 'id_desc', 0, 15, 20, 1)) && p('pager,hasApiList') && e('pager,1');
r($docTest->assignApiVarForSpaceTest('product', 'all', 'lib', 1, array(1 => 'testlib'), 1, 0, 0, 'id_desc', 0, 20, 20, 1)) && p('canExport') && e('0');
r($docTest->assignApiVarForSpaceTest('project', 'all', 'lib', 1, array(1 => 'testlib'), 1, 0, 0, 'id_desc', 0, 20, 20, 1)) && p('canExport') && e('0');
r($docTest->assignApiVarForSpaceTest('product', 'all', 'lib', 1, array(1 => 'testlib'), 1, 0, 0, 'id_desc', 0, 50, 20, 1)) && p('pager') && e('pager');
r($docTest->assignApiVarForSpaceTest('product', 'all', 'lib', 1, array(1 => 'testlib'), 1, 0, 0, 'id_desc', 0, 100, 50, 2)) && p('pager') && e('pager');