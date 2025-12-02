#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::createFreeMindXmlDoc();
timeout=0
cid=19087

- 执行testcaseTest模块的createFreeMindXmlDocTest方法，参数是1, '产品1', $context  @DOMDocument
- 执行testcaseTest模块的createFreeMindXmlDocTest方法，参数是2, '', $context  @DOMDocument
- 执行testcaseTest模块的createFreeMindXmlDocTest方法，参数是3, '测试产品', $context  @DOMDocument
- 执行testcaseTest模块的createFreeMindXmlDocTest方法，参数是0, 'Test Product', $context  @DOMDocument
- 执行testcaseTest模块的createFreeMindXmlDocTest方法，参数是5, '<产品>', $context  @DOMDocument

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,测试产品,Test Product,<产品>');
$product->status->range('normal');
$product->gen(5);

su('admin');

$testcaseTest = new testcaseZenTest();

$context = array('config' => array(), 'moduleList' => array(), 'caseList' => array(), 'sceneList' => array(), 'topScenes' => array());

r(get_class($testcaseTest->createFreeMindXmlDocTest(1, '产品1', $context))) && p() && e('DOMDocument');
r(get_class($testcaseTest->createFreeMindXmlDocTest(2, '', $context))) && p() && e('DOMDocument');
r(get_class($testcaseTest->createFreeMindXmlDocTest(3, '测试产品', $context))) && p() && e('DOMDocument');
r(get_class($testcaseTest->createFreeMindXmlDocTest(0, 'Test Product', $context))) && p() && e('DOMDocument');
r(get_class($testcaseTest->createFreeMindXmlDocTest(5, '<产品>', $context))) && p() && e('DOMDocument');