#!/usr/bin/env php
<?php

/**

title=测试 treeZen::setRoot();
timeout=0
cid=0

- 执行treeTest模块的setRootTest方法，参数是0, 'line', ''
 - 属性id @0
 - 属性rootType @line
- 执行treeTest模块的setRootTest方法，参数是0, 'host', ''
 - 属性id @0
 - 属性rootType @line
- 执行treeTest模块的setRootTest方法，参数是0, 'datasource', ''
 - 属性id @0
 - 属性rootType @line
- 执行treeTest模块的setRootTest方法，参数是1, 'bug', '' 属性rootType @product
- 执行treeTest模块的setRootTest方法，参数是1, 'story', '' 属性rootType @product

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/treezen.unittest.class.php';

zenData('product')->loadYaml('product', false, 2)->gen(5);

su('admin');

$treeTest = new treeTest();

r($treeTest->setRootTest(0, 'line', '')) && p('id,rootType') && e('0,line');
r($treeTest->setRootTest(0, 'host', '')) && p('id,rootType') && e('0,line');
r($treeTest->setRootTest(0, 'datasource', '')) && p('id,rootType') && e('0,line');
r($treeTest->setRootTest(1, 'bug', '')) && p('rootType') && e('product');
r($treeTest->setRootTest(1, 'story', '')) && p('rootType') && e('product');