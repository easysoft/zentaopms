#!/usr/bin/env php
<?php

/**

title=测试 treeZen::setRoot();
timeout=0
cid=0

- 执行treeTest模块的setRootTest方法，参数是0, 'line', '' 属性rootType @line
- 执行treeTest模块的setRootTest方法，参数是0, 'host', '' 属性rootType @line
- 执行treeTest模块的setRootTest方法，参数是0, 'datasource', '' 属性rootType @line
- 执行treeTest模块的setRootTest方法，参数是1, 'caselib', '' 属性rootType @lib
- 执行treeTest模块的setRootTest方法，参数是1, 'story', '' 属性rootType @product

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/treezen.unittest.class.php';

zenData('product')->loadYaml('product', false, 2)->gen(10);
zenData('testsuite')->loadYaml('testsuite', false, 2)->gen(5);

su('admin');

$treeTest = new treeTest();

// 测试步骤1: 测试viewType='line'的情况,应返回产品线对象,rootType为line
r($treeTest->setRootTest(0, 'line', '')) && p('rootType') && e('line');

// 测试步骤2: 测试viewType='host'的情况,应返回主机分组维护对象,rootType为line
r($treeTest->setRootTest(0, 'host', '')) && p('rootType') && e('line');

// 测试步骤3: 测试viewType='datasource'的情况,应返回管理对象,rootType为line
r($treeTest->setRootTest(0, 'datasource', '')) && p('rootType') && e('line');

// 测试步骤4: 测试viewType包含'caselib'的情况,应返回用例库对象,rootType为lib
r($treeTest->setRootTest(1, 'caselib', '')) && p('rootType') && e('lib');

// 测试步骤5: 测试viewType='story'的情况,应返回产品对象,rootType为product
r($treeTest->setRootTest(1, 'story', '')) && p('rootType') && e('product');