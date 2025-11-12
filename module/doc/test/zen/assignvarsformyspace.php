#!/usr/bin/env php
<?php

/**

title=测试 docZen::assignVarsForMySpace();
timeout=0
cid=0

- 执行docTest模块的assignVarsForMySpaceTest方法，参数是'mine', 0, 1, 0, 'all', 0, 'id_desc', $docs, $pager, $libs, ''
 - 属性type @mine
 - 属性spaceType @mine
 - 属性libType @lib
- 执行docTest模块的assignVarsForMySpaceTest方法，参数是'mine', 0, 5, 3, 'all', 0, 'id_desc', $docs, $pager, $libs, ''
 - 属性libID @5
 - 属性moduleID @3
- 执行docTest模块的assignVarsForMySpaceTest方法，参数是'mine', 0, 1, 0, 'bysearch', 0, 'title_asc', $docs, $pager, $libs, ''
 - 属性browseType @bysearch
 - 属性orderBy @title_asc
- 执行docTest模块的assignVarsForMySpaceTest方法，参数是'mine', 0, 1, 0, 'all', 0, 'order_asc', $docs, $pager, $libs, '' 属性orderBy @order_asc
- 执行docTest模块的assignVarsForMySpaceTest方法，参数是'mine', 0, 1, 0, 'all', 0, 'id_desc', $docs, $pager, $libs, '' 属性canUpdateOrder @0
- 执行docTest模块的assignVarsForMySpaceTest方法，参数是'mine', 0, 1, 0, 'all', 0, 'id_desc', $docs, $pager, $libs, 'MyTitle'
 - 属性objectTitle @MyTitle
 - 属性objectID @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doczen.unittest.class.php';

zendata('doclib')->gen(10);
zendata('doc')->gen(20);
zendata('user')->gen(10);

su('admin');

$docTest = new docZenTest();

global $tester;
$tester->app->loadClass('pager', true);
ob_start();
$pager = new pager(0, 20, 1);
ob_end_clean();

$docs = array();
$lib1 = new stdclass();
$lib1->id = 1;
$lib1->name = 'testlib';
$lib1->type = 'mine';

$lib2 = new stdclass();
$lib2->id = 2;
$lib2->name = 'mylib';
$lib2->type = 'mine';

$libs = array(1 => $lib1, 2 => $lib2);

r($docTest->assignVarsForMySpaceTest('mine', 0, 1, 0, 'all', 0, 'id_desc', $docs, $pager, $libs, '')) && p('type,spaceType,libType') && e('mine,mine,lib');
r($docTest->assignVarsForMySpaceTest('mine', 0, 5, 3, 'all', 0, 'id_desc', $docs, $pager, $libs, '')) && p('libID,moduleID') && e('5,3');
r($docTest->assignVarsForMySpaceTest('mine', 0, 1, 0, 'bysearch', 0, 'title_asc', $docs, $pager, $libs, '')) && p('browseType,orderBy') && e('bysearch,title_asc');
r($docTest->assignVarsForMySpaceTest('mine', 0, 1, 0, 'all', 0, 'order_asc', $docs, $pager, $libs, '')) && p('orderBy') && e('order_asc');
r($docTest->assignVarsForMySpaceTest('mine', 0, 1, 0, 'all', 0, 'id_desc', $docs, $pager, $libs, '')) && p('canUpdateOrder') && e('0');
r($docTest->assignVarsForMySpaceTest('mine', 0, 1, 0, 'all', 0, 'id_desc', $docs, $pager, $libs, 'MyTitle')) && p('objectTitle,objectID') && e('MyTitle,0');