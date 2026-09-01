#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createBuildinField();
timeout=0
cid=15833

- 执行convertTest模块的createBuildinFieldTest方法，参数是'testmodule', array  @1
- 执行convertTest模块的createBuildinFieldTest方法，参数是'testmodule', array  @1
- 执行convertTest模块的createBuildinFieldTest方法，参数是'', array  @1
- 执行convertTest模块的createBuildinFieldTest方法，参数是'testmodule2', array  @1
- 执行convertTest模块的createBuildinFieldTest方法，参数是'testmodule3', array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('workflowfield')->gen(0);
zenData('workflow')->gen(0);

global $config, $tester;
if(!isset($config->workflowfield)) $config->workflowfield = new stdclass();
if(!isset($config->workflowfield->numberTypes)) $config->workflowfield->numberTypes = array('int', 'decimal', 'float');

su('admin');

$convertTest = new convertTaoTest();

r($convertTest->createBuildinFieldTest('testmodule', array(), array(), false)) && p() && e('1');
r($convertTest->createBuildinFieldTest('testmodule', array(), array(), true)) && p() && e('1');
r($convertTest->createBuildinFieldTest('', array(), array(), false)) && p() && e('1');
r($convertTest->createBuildinFieldTest('testmodule2', array(), array())) && p() && e('1');
r($convertTest->createBuildinFieldTest('testmodule3', array(), array(), null)) && p() && e('1');

/* 清理本用例创建的流程数据，避免残留空 table 的 workflow 污染 objectTables。 */
zenData('workflow')->gen(0);
zenData('workflowaction')->gen(0);
zenData('workflowfield')->gen(0);
zenData('workflowlayout')->gen(0);
zenData('workflowlabel')->gen(0);
zenData('workflowrule')->gen(0);
zenData('workflowdatasource')->gen(0);
zenData('workflowrelation')->gen(0);
zenData('workflowlinkdata')->gen(0);
zenData('workflowversion')->gen(0);
zenData('workflowreport')->gen(0);
zenData('workflowsql')->gen(0);
