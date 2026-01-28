#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildFieldConfigSchemeIssueTypeData();
timeout=0
cid=15810

- 执行convertTest模块的buildFieldConfigSchemeIssueTypeDataTest方法，参数是array 属性id @1
- 执行convertTest模块的buildFieldConfigSchemeIssueTypeDataTest方法，参数是array 属性issuetype @~~
- 执行convertTest模块的buildFieldConfigSchemeIssueTypeDataTest方法，参数是array 属性fieldconfigscheme @~~
- 执行convertTest模块的buildFieldConfigSchemeIssueTypeDataTest方法，参数是array 属性fieldconfiguration @~~
- 执行convertTest模块的buildFieldConfigSchemeIssueTypeDataTest方法，参数是array 属性issuetype @~~
- 执行convertTest模块的buildFieldConfigSchemeIssueTypeDataTest方法，参数是array 属性issuetype @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$convertTest = new convertTaoTest();

r($convertTest->buildFieldConfigSchemeIssueTypeDataTest(array('id' => '1', 'issuetype' => 'bug', 'fieldconfigscheme' => 'scheme1', 'fieldconfiguration' => 'config1'))) && p('id') && e('1');
r($convertTest->buildFieldConfigSchemeIssueTypeDataTest(array('id' => '2', 'fieldconfigscheme' => 'scheme2', 'fieldconfiguration' => 'config2'))) && p('issuetype') && e('~~');
r($convertTest->buildFieldConfigSchemeIssueTypeDataTest(array('id' => '3', 'issuetype' => 'task', 'fieldconfiguration' => 'config3'))) && p('fieldconfigscheme') && e('~~');
r($convertTest->buildFieldConfigSchemeIssueTypeDataTest(array('id' => '4', 'issuetype' => 'story', 'fieldconfigscheme' => 'scheme4'))) && p('fieldconfiguration') && e('~~');
r($convertTest->buildFieldConfigSchemeIssueTypeDataTest(array('id' => '5'))) && p('issuetype') && e('~~');
r($convertTest->buildFieldConfigSchemeIssueTypeDataTest(array('id' => '6', 'issuetype' => '', 'fieldconfigscheme' => '', 'fieldconfiguration' => ''))) && p('issuetype') && e('~~');